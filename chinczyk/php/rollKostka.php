<?php

session_start();

if(isset($_SESSION['player_id']) && isset($_SESSION['game_id']) && isset($_POST['rzut'])){
    require_once("mongoConnectorClass.php");

    $mongo = new MongoConnector();
    
    $filter = ["_id" => $_SESSION['game_id']];

    $update = ['$set' => ['current.move' => (int)$_POST['rzut']]];

    $mongo->updateData($filter, $update);

    $update = ['$set' => ['current.last_move' => (int)$_POST['rzut']]];

    $mongo->updateData($filter, $update);
}