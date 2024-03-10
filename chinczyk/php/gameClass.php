<?php

class Game{
    private $id;
    private $status; // 0-czeka na innych,  1-w trakcie gry, 2-zakonczona
    private $numberOfPlayer = 0;
    private $players = array();
    private $lastMove = array();

    public function __construct(){
        require_once("mongoConnectorClass.php");

        $mongo = new MongoConnector();

        $filter = [
            '$and' => [
                [
                    '$expr' => [
                        '$lt' => [
                            ['$size' => '$players'],
                            4
                        ]
                    ]
                ],
                [
                    'status' => 0
                ]
            ]
        ];
        $data = $mongo->getData($filter);
        if(count($data) == 0){
            $_id = new MongoDB\BSON\ObjectId;
            $new = ["_id" => $_id, "status" => 0, "players" => [], "pionki" => [], 'current' => [
                'player' => -1,
                'move' => -1,
                'last_move' => 1,
                'timer' => -1
            ]];
            $this->id = $_id;
            $this->status = 0;

            $mongo->insertData($new);
        }
        else{
            $first = $data[0];
            $this->id = $first->_id;
            $players = $first->players;
            foreach($players as $index => $player){
                require_once("userClass.php");

                $user = new User();
                $user->set_id($index);
                $user->set_game_id($this->id);
                $user->set_nick($player->nick);

                $this->add_player($user);
            }
            $pionki = $first->pionki;
        }
    }

    public function get_id() {
        return $this->id;
    }

    public function get_lastMove() {
        return $this->lastMove;
    }

    private function setNumOfPlayers(){
        $this->numberOfPlayer = count($this->players);
    }

    public function add_player($player) {
        array_push($this->players, $player);
        $this->setNumOfPlayers();
    }

    public function remove_player($playerId){
        if (($key = array_search($playerId, $this->players)) !== false) {
            unset($this->players[$key]);
            $this->setNumOfPlayers();
        }
    }

    public function get_players() {
        return $this->players;
    }

    public function get_number_of_players() {
        return $this->numberOfPlayer;
    }

    public function getAllData(){
        return $this->id . " | " . $this->numberOfPlayer . " | " . implode(" ", $this->players);
    }
}