<?php

// bases
$red_baza = ['1-1', '1-2', '2-1', '2-2'];
$blue_baza = ['1-10', '1-11', '2-10', '2-11'];
$yellow_baza = ['10-1', '10-2', '11-1', '11-2'];
$green_baza = ['10-10', '10-11', '11-10', '11-11'];
// circles
$red_circle = ['5-1', '5-2', '5-3', '5-4', '5-5', '4-5', '3-5', '2-5', '1-5', '1-6', '1-7', '2-7', '3-7', '4-7', '5-7', '5-8', '5-9', '5-10', '5-11', '6-11', '7-11', '7-10',
'7-9','7-8','7-7', '8-7', '9-7', '10-7', '11-7', '11-6', '11-5', '10-5', '9-5', '8-5', '7-5', '7-4', '7-3', '7-2', '7-1',
'6-1', '6-2', '6-3', '6-4', '6-5'];
$blue_circle = ['1-7', '2-7', '3-7', '4-7', '5-7', '5-8', '5-9', '5-10', '5-11', '6-11', '7-11', '7-10',
'7-9','7-8','7-7', '8-7', '9-7', '10-7', '11-7', '11-6', '11-5', '10-5', '9-5', '8-5', '7-5', '7-4', '7-3', '7-2', '7-1',
'6-1', '5-1', '5-2', '5-3', '5-4', '5-5', '4-5', '3-5', '2-5', '1-5', '1-6', '2-6', '3-6', '4-6', '5-6'];
$yellow_circle = ['11-5', '10-5', '9-5', '8-5', '7-5', '7-4', '7-3', '7-2', '7-1',
'6-1', '5-1', '5-2', '5-3', '5-4', '5-5', '4-5', '3-5', '2-5', '1-5', '1-6', '1-7', '2-7', '3-7', '4-7', '5-7', '5-8', '5-9', '5-10', '5-11', '6-11', '7-11', '7-10',
'7-9','7-8','7-7', '8-7', '9-7', '10-7', '11-7', '11-6', '10-6', '9-6', '8-6', '7-6'];
$green_circle = ['7-11', '7-10',
'7-9','7-8','7-7', '8-7', '9-7', '10-7', '11-7', '11-6', '11-5', '10-5', '9-5', '8-5', '7-5', '7-4', '7-3', '7-2', '7-1',
'6-1', '5-1', '5-2', '5-3', '5-4', '5-5', '4-5', '3-5', '2-5', '1-5', '1-6', '1-7', '2-7', '3-7', '4-7', '5-7', '5-8', '5-9', '5-10', '5-11', '6-11', '6-10', '6-9', '6-8', '6-7'];
// boxes
$red_box = ['6-2', '6-3', '6-4', '6-5'];
$blue_box = ['2-6', '3-6', '4-6', '5-6'];
$yellow_box= ['10-6', '9-6', '8-6', '7-6'];
$green_box = ['6-10', '6-9', '6-8', '6-7'];



// --- main code:

if(isset($_POST['pionek'])){
    move($_POST['action'], $_POST['pionek'], $_POST['position'], $red_baza, $blue_baza, $yellow_baza, $green_baza, $red_circle, $blue_circle, $yellow_circle, $green_circle, $red_box, $blue_box, $yellow_box, $green_box);
}

function move($action, $pionek, $position, $red_baza, $blue_baza, $yellow_baza, $green_baza, $red_circle, $blue_circle, $yellow_circle, $green_circle, $red_box, $blue_box, $yellow_box, $green_box){
    $pionek_id = substr($pionek, -1);
    $parts = explode("-", $pionek);
    $color = $parts[1];

    session_start();
    $game_id = $_SESSION["game_id"];
    $player_id = $_SESSION["player_id"];

    require_once('mongoConnectorClass.php');
    $mongo = new MongoConnector();
    $filter = [
        '_id' => new MongoDB\BSON\ObjectId($_SESSION['game_id'])
    ];            
    $res = $mongo->getData($filter)[0];

    $curr_player = $res->current->player;
    $ilosc = $res->current->move;

    if($curr_player == $player_id && $ilosc > 0 && $ilosc < 7){
        $color_based_on_player_id = colFromPlayerID($player_id);

        if($color_based_on_player_id == $color){

            $nextTd_id = null;

            switch($player_id){
                case 0:
                    if(in_array($position, $red_baza) && ($ilosc == 6 || $ilosc == 1)){ // gdy wychodzi z bazy musi mieć 6 lub 1
                        $nextTd_id = $red_circle[0];
                    }
                    else if(in_array($position, $red_circle)){
                        $nextTd_id = nextPos($position, $red_circle, $ilosc); // gdy nie wychodzi bierze kolejną pozycję w tablicy
                        if(in_array($nextTd_id,$red_box) && isPionekHere($nextTd_id)){ // gdy wchodzi do boxa nie może stać z innym pionkiem w jednym polu
                            $nextTd_id = null;
                        }
                    }
                    break;
                case 1:
                    if(in_array($position, $blue_baza) && ($ilosc == 6 || $ilosc == 1)){
                        $nextTd_id = $blue_circle[0];
                    }
                    else if(in_array($position, $blue_circle)){
                        $nextTd_id = nextPos($position, $blue_circle, $ilosc);
                        if(in_array($nextTd_id,$blue_box) && isPionekHere($nextTd_id)){
                            $nextTd_id = null;
                        }
                    }
                    break;
                case 2:
                    if(in_array($position, $yellow_baza) && ($ilosc == 6 || $ilosc == 1)){
                        $nextTd_id = $yellow_circle[0];
                    }
                    else if(in_array($position, $yellow_circle)){
                        $nextTd_id = nextPos($position, $yellow_circle, $ilosc);
                        if(in_array($nextTd_id,$yellow_box) && isPionekHere($nextTd_id)){
                            $nextTd_id = null;
                        }
                    }
                    break;
                case 3:
                    if(in_array($position, $green_baza) && ($ilosc == 6 || $ilosc == 1)){
                        $nextTd_id = $green_circle[0];
                    }
                    else if(in_array($position, $green_circle)){
                        $nextTd_id = nextPos($position, $green_circle, $ilosc);
                        if(in_array($nextTd_id,$green_box) && isPionekHere($nextTd_id)){
                            $nextTd_id = null;
                        }
                    }
                    break;
            }


            if($nextTd_id == null && $action == 'check' && $ilosc != 6){
                echo json_encode(["next" => "none", "six" => "nope"]);
            }
            else if($nextTd_id == null && $action == 'check' && $ilosc == 6){
                echo json_encode(["next" => "none", "six" => "yeah"]);
                $update = [
                    '$set' => [
                        'current.move' => -1,
                    ]
                ];

                $mongo->updateData($filter, $update);
            }
            else if($nextTd_id != null && $action != "check"){
                require_once("pionekClass.php");
                $pionek = new Pionek($player_id, $pionek_id, $game_id, "update");

                if($action == 'move'){
                    $pionek->update_position($nextTd_id);
                    if($ilosc != 6){
                        require_once('nextPlayer.php');
                    }
                    else{
                        $update = [
                            '$set' => [
                                'current.move' => -1,
                            ]
                        ];
        
                        $mongo->updateData($filter, $update);
                    }

                    $endWinner = checkEnd($red_box, $blue_box, $green_box, $yellow_box);
                    if($endWinner != ""){
                        echo json_encode(["next" => "end", "winner" => $endWinner]);

                        $update = ['$set' => ['status' => 2]];

                        $mongo->updateData($filter, $update);
                    }
                }
                else{
                    echo json_encode(["next" => $nextTd_id]);
                }
            }
        }
    }
}

function colFromPlayerID($player_id){
    $color_based_on_player_id = "";
    if($player_id == 0){
        $color_based_on_player_id = "red";
    }
    else if($player_id == 1){
        $color_based_on_player_id = "blue";
    }
    else if($player_id == 2){
        $color_based_on_player_id = "yellow";
    }
    else if($player_id == 3){
        $color_based_on_player_id = "green";
    }

    return $color_based_on_player_id;
}

function nextPos($pos, $circle, $ilosc){
    $next_id = array_search($pos, $circle);;
    for($i = 0; $i < $ilosc; $i++){
        if($next_id + 1 < count($circle)){
            $next_id++;
        }
        else{
            $next_id = null;
            break;
        }
    }
    
    if($next_id != null){
        return $circle[$next_id];
    }
    else{
        return null;
    }
}

function isPionekHere($pos_){
    require_once('mongoConnectorClass.php');
    $mongo = new MongoConnector();
    $filter = [
        '_id' => new MongoDB\BSON\ObjectId($_SESSION['game_id'])
    ];            
    $res = $mongo->getData($filter)[0];

    $pionkis = $res->pionki;

    $found = false;
    for($i = 0; $i < count($pionkis); $i++){
        if($pionkis[$i]->position == $pos_){
            $found = true;
            break;
        }
    }

    return $found;
}

function checkEnd($red_box, $blue_box, $green_box, $yellow_box){
    $endTemp = 0;
    $endColor = "";
    if($endColor == ""){
        foreach ($red_box as $box_p) {
            if (isPionekHere($box_p)) {
                $endTemp++;
                $endColor = "red";
            } else {
                $endTemp = 0;
                $endColor = "";
                break;
            }
        }
    }
    if($endColor == ""){
        foreach ($blue_box as $box_p) {
            if (isPionekHere($box_p)) {
                $endTemp++;
                $endColor = "blue";
            } else {
                $endTemp = 0;
                $endColor = "";
                break;
            }
        }
    }
    if($endColor == ""){
        foreach ($yellow_box as $box_p) {
            if (isPionekHere($box_p)) {
                $endTemp++;
                $endColor = "yellow";
            } else {
                $endTemp = 0;
                $endColor = "";
                break;
            }
        }
    }
    if($endColor == ""){
        foreach ($green_box as $box_p) {
            if (isPionekHere($box_p)) {
                $endTemp++;
                $endColor = "green";
            } else {
                $endTemp = 0;
                $endColor = "";
                break;
            }
        }
    }
    return $endColor;
}