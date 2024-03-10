<?php
session_start();

if(isset($_SESSION['player_id']) && isset($_SESSION['game_id'])){

    $lang = $_SESSION['lang'];

    putenv("LC_ALL=$lang");
    setlocale(LC_MESSAGES, "$lang");

    bindtextdomain("game", "../locale");
    textdomain("game");

    $gameidGT = gettext("GAME ID");

    echo "<p class='game_id_info'>$gameidGT: <span>".$_SESSION['game_id']."</span></p>";

    require_once("mongoConnectorClass.php");

    $mongo = new MongoConnector();
    
    $filter = ["_id" => $_SESSION['game_id']];

    $data = $mongo->getData($filter)[0];

    $players = $data->players;

    $colors = ["red", "lightblue", "yellow", "lightgreen"];
    $p_id = $_SESSION['player_id'];

    $youGT = gettext("you");

    echo "<div class='players'>";
    
    for($i = 0; $i<4; $i++){
        if(isset($players[$i])){
            $col = $colors[$i];
            if($players[$i]->status == 0 || $players[$i]->status == 2){
                $col = "lightgray";
            }
            $whoami = $p_id==$i?" ($youGT)":"";
            echo "<div style='background-color:$col'>" . $players[$i]->nick . $whoami . "</div>";
        }
        else{
            echo "<div style='background-color:lightgray'></div>";
        }
    }
    echo "</div>";

    $timer = $data->current->timer;

    $curr_player = (int)$data->current->player;
    if($curr_player >= 0 && $curr_player < 4){
        $col = $colors[$curr_player];
    }
    else{
        $col = 'lightgray';
    }

    if($timer >= 0){
        echo "<p id='timer' style='background-color:$col'>$timer</p>";
    }
}