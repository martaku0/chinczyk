<?php

session_start();

if(isset($_SESSION['player_id']) && isset($_SESSION['game_id'])){
    require_once("mongoConnectorClass.php");

    $mongo = new MongoConnector();
    
    $filter = ["_id" => $_SESSION['game_id']];

    $res = $mongo->getData($filter)[0];

    $timer = $res->current->timer;

    if($res->status == 0 && count($res->players) >= 2){
        $status_sumary = 0;

        $next_player = null;

        for($i = 0; $i<count($res->players); $i++){
            if($res->players[$i]->status == 1){
                $status_sumary++;
            }
        }

        for($i = 0; $i<count($res->players); $i++){
            if($res->players[$i]->status == 1){
                $next_player = $res->players[$i]->id;
                break;
            }
        }

        if($status_sumary >= 2 && $timer == -1){
            $update = [
                '$set' => [
                    'current.timer' => 10,
                ]
            ];
            $mongo->updateData($filter, $update);
        }
        else if($status_sumary >= 2 && $timer == 0){
            $update = [
                '$set' => [
                    'status' => 1,
                ]
            ];

            $mongo->updateData($filter, $update);

            if($res->current->player == -1){
                $update = [
                    '$set' => [
                        'current.player' => (int)$next_player
                    ]
                ];

                $mongo->updateData($filter, $update);
            }

            require_once("timer.php");

            $res = $mongo->getData($filter)[0];
        }
        else if($status_sumary < 2){
            $update = [
                '$set' => [
                    'current.timer' => -1,
                ]
            ];

            $mongo->updateData($filter, $update);
        }
    }

    $your_player_status = null;
    for($i = 0; $i<count($res->players); $i++) {
        if ($res->players[$i]->id == $_SESSION['player_id']) {
            $your_player_status = $res->players[$i]->status;
            break;
        }
    }

    if($your_player_status == 2){
        session_destroy();
        echo json_encode([
            "afk" => "true"
        ]);
        exit;
    }

    $data = [
        "game_id" => $_SESSION['game_id'],
        "game_status" => $res->status,
        "current_player_id" => $res->current->player,
        "current_move" => $res->current->move,
        "last_move" => $res->current->last_move,
        "your_player_id" => $_SESSION['player_id'],
        "timer" => $res->current->timer,
        'your_player_status' => $your_player_status,
    ];
    
    echo json_encode($data);
}