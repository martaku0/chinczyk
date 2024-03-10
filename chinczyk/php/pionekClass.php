<?php

class Pionek{
    private $position;
    private $player_id;
    private $color;
    private $id;
    private $game_id;

    public function __construct($p_id, $id, $g_id, $action){

        $this->player_id = $p_id;
        $this->id = $id;
        $this->game_id = $g_id;
        $this->set_color();

        if($action == 'new'){
            switch($this->color){
                case "red":
                    switch($this->id){
                        case 0:
                            $this->set_position("1-1");
                            break;
                        case 1:
                            $this->set_position("1-2");
                            break;
                        case 2:
                            $this->set_position("2-1");
                            break;
                        case 3:
                            $this->set_position("2-2");
                            break;
                    }
                    break;
                case "blue":
                    switch($this->id){
                        case 0:
                            $this->set_position("1-10");
                            break;
                        case 1:
                            $this->set_position("1-11");
                            break;
                        case 2:
                            $this->set_position("2-10");
                            break;
                        case 3:
                            $this->set_position("2-11");
                            break;
                    }
                    break;
                case "yellow":
                    switch($this->id){
                        case 0:
                            $this->set_position("10-1");
                            break;
                        case 1:
                            $this->set_position("10-2");
                            break;
                        case 2:
                            $this->set_position("11-1");
                            break;
                        case 3:
                            $this->set_position("11-2");
                            break;
                    }
                    break;
                case "green":
                    switch($this->id){
                        case 0:
                            $this->set_position("10-10");
                            break;
                        case 1:
                            $this->set_position("10-11");
                            break;
                        case 2:
                            $this->set_position("11-10");
                            break;
                        case 3:
                            $this->set_position("11-11");
                            break;
                    }
                    break;
            }
        }

    }

    private function set_position($pos){
        $this->position = $pos;

        require_once("mongoConnectorClass.php");

        $mongo = new MongoConnector();

        $filter = ['_id' => $this->game_id];

        $newPionek = [
            'id' => $this->id,
            'position' => $this->position,
            'color' => $this->color,
            'player_id' => $this->player_id
        ];

        $p_id = $this->player_id;
        $update = ['$push' => ['pionki' => $newPionek]];
        $mongo->updateData($filter, $update);
    }

    public function update_position($pos){
        $this->position = $pos;

        require_once("mongoConnectorClass.php");

        $mongo = new MongoConnector();

        $filter = [
            '_id' => new MongoDB\BSON\ObjectId($this->game_id),
            'pionki' => [
                '$elemMatch' => [
                    'id' => (int)$this->id,
                    'color' => $this->color
                ]
            ]
        ];
        
        $update = [
            '$set' => [
                'pionki.$.position' => $pos
            ]
        ];

        $this->zbijanie($pos);

        $mongo->updateData($filter, $update);
    }

    private function zbijanie($pos){
        $red_baza = ['1-1', '1-2', '2-1', '2-2'];
        $blue_baza = ['1-10', '1-11', '2-10', '2-11'];
        $green_baza = ['10-10', '10-11', '11-10', '11-11'];
        $yellow_baza = ['10-1', '10-2', '11-1', '11-2'];

        $mongo = new MongoConnector();
        $filter = [
            '_id' => new MongoDB\BSON\ObjectId($_SESSION['game_id'])
        ];            
        $res = $mongo->getData($filter)[0];

        $pionkis = $res->pionki;
        for($i = 0; $i<count($pionkis); $i++){
            var_dump($pionkis[$i]);
            if($pos == $pionkis[$i]->position && $this->color != $pionkis[$i]->color){
                $filter = [
                    '_id' => new MongoDB\BSON\ObjectId($this->game_id),
                    'pionki' => [
                        '$elemMatch' => [
                            'id' => (int)$pionkis[$i]->id,
                            'color' => $pionkis[$i]->color
                        ]
                    ]
                ];
                $nextPos = 0;
                if($pionkis[$i]->color == "red"){
                    $nextPos = $red_baza[(int)$pionkis[$i]->id];
                }
                else if($pionkis[$i]->color == "blue"){
                    $nextPos = $blue_baza[(int)$pionkis[$i]->id];
                }
                else if($pionkis[$i]->color == "green"){
                    $nextPos = $green_baza[(int)$pionkis[$i]->id];
                }
                else if($pionkis[$i]->color == "yellow"){
                    $nextPos = $yellow_baza[(int)$pionkis[$i]->id];
                }
                $update = [
                    '$set' => [
                        'pionki.$.position' => $nextPos
                    ]
                ];

                $mongo->updateData($filter, $update);
            }
        }
    }

    public function get_position(){
        return $this->position;
    }

    public function get_id(){
        return $this->id;
    }

    private function set_color(){
        if($this->player_id == 0){
            $this->color = "red";
        }
        else if($this->player_id == 1){
            $this->color = "blue";
        }
        else if($this->player_id == 2){
            $this->color = "yellow";
        }
        else if($this->player_id == 3){
            $this->color = "green";
        }
    }
    public function get_player_id(){
        return $this->player_id;
    }

    public function get_color(){
        return $this->color;
    }

    public function get_game_id(){
        return $this->game_id;
    }
}