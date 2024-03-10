<?php
session_start();

if(isset($_SESSION['player_id']) && isset($_SESSION['game_id'])){

    require_once("mongoConnectorClass.php");

    $mongo = new MongoConnector();

    $filter = ["_id" => $_SESSION['game_id']];

    $data = $mongo->getData($filter)[0];

    $pionkis = $data->pionki;

    $results = [];

    foreach($pionkis as $inx=>$pionek){
        $pos = $pionek->position;
        $color = $pionek->color;
        $id = $pionek->id;

        $results[$inx] = [
            "id" => $id,
            "color" => $color,
            "position" => $pos
        ];
    }

    echo json_encode($results);
}