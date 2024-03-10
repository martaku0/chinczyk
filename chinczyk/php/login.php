<?php
require_once("gameClass.php");
require_once("userClass.php");
require_once("pionekClass.php");
require_once("mongoConnectorClass.php");

function logIn(){
    if(isset($_POST["nick"])){

        $pionki = array();

        $nick = $_POST["nick"];

        $newGame = new Game();
        $newGame_id = $newGame->get_id();

        $newUser = new User();
        $newUserId = $newGame->get_number_of_players();
        $newUser->set_id($newUserId);
        $newUser->set_game_id($newGame_id);
        $newUser->set_nick($nick);
        $newUser->set_status(0);

        $newGame->add_player($newUser);

        $mongo = new MongoConnector();

        $newPlayer = [
            "id" => $newUser->get_id(),
            "nick" => $newUser->get_nick(),
            "status" => $newUser->get_status()
        ];

        $filter = ['_id' => $newGame->get_id()];
        $update = ['$push' => ['players' => $newPlayer]];
        $mongo->updateData($filter, $update);

        for($i = 0; $i<4; $i++){
            $pionek = new Pionek($newUserId, $i,$newGame_id, "new");
            array_push($pionki, $pionek);
        }

        session_start();
        $_SESSION['game_id'] = $newGame_id;
        $_SESSION['player_id'] = $newUserId;
        
        header( "Location: game.php" );
        exit;
    }
}

logIn();