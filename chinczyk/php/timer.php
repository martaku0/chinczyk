<?php

session_start();

require_once("mongoConnectorClass.php");
$mongo = new MongoConnector();
$filter = ["_id" => $_SESSION['game_id']];

$res = $mongo->getData($filter)[0];
$sec = $res->current->timer;
if($sec - 1 >= 0){
    $update = [
        '$set' => ['current.timer' => $sec - 1]
    ];
    $mongo->updateData($filter, $update);
}
else{
    $update = [
        '$set' => ['current.timer' => 30]
    ];
    $mongo->updateData($filter, $update);
    if(isset($_POST['action'])){
        $curr_player = $res->current->player;
        $filter = [
            '_id' => $_SESSION['game_id'],
            'players' => [
                '$elemMatch' => [
                    'id' => $curr_player
                ]
            ]
        ];
        $update = [
            '$set' => [
                'players.$.status' => 2 // afk
            ]
        ];
        $mongo->updateData($filter, $update);
        require_once("nextPlayer.php");
    }
}

