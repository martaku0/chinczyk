<?php

require_once("mongoConnectorClass.php");

session_start();

$mongo = new MongoConnector();
$filter = [
    '_id' => new MongoDB\BSON\ObjectId($_SESSION['game_id'])
];            
$res = $mongo->getData($filter)[0];

$curr_player = $res->current->player;

$players_arr = array();

for($i = 0; $i<count($res->players); $i++){
    if($res->players[$i]->status == 1){
        $p_id = $res->players[$i]->id;
        array_push($players_arr, $p_id);
    }
}

$inx_of_curr_p = array_search($curr_player,$players_arr);
$inx_of_next_p = null;
if($inx_of_curr_p === false){
    $inx_of_curr_p = 0;
}
else{
    $inx_of_next_p = $inx_of_curr_p+1;
}
if($inx_of_curr_p >= count($players_arr)-1){
    $inx_of_next_p = 0;
}

$next_p = $players_arr[$inx_of_next_p];

$update = [
    '$set' => [
        'current.move' => -1,
        'current.player' => $next_p,
        'current.timer' => 30
    ]
];

$mongo->updateData($filter, $update);