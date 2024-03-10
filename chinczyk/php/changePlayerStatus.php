<?php

session_start();

if(isset($_SESSION['player_id']) && isset($_SESSION['game_id'])){
    require_once('userClass.php');
    
    $lang = $_SESSION['lang'];

    putenv("LC_ALL=$lang");
    setlocale(LC_MESSAGES, "$lang");

    bindtextdomain("game", "../locale");
    textdomain("game");

    if(isset($_POST['status'])){
        $player = new User();
        $player->set_game_id($_SESSION['game_id']);
        $player->set_id($_SESSION['player_id']);

        $status = $_POST['status'];
        $st = 0;
        if($status == gettext("ready to play")){
            $st = 1;
        }

        $player->set_status($st);

        if($st == 0){
            echo gettext("ready to play");
        }
        else if($st == 1){
            echo gettext("waiting for others");
        }
    }
}