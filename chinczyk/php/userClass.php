<?php

class User{
    private $id;
    private $nick;
    private $game_id;
    private $status; // 0 - niegotowy, 1 - gotowy, 2 - afk
    
    public function set_id($id) {
        $this->id = $id;
    }
    public function get_id() {
        return $this->id;
    }

    public function set_status($status) {
        $this->status = $status;
        require_once('mongoConnectorClass.php');

        $mongo = new MongoConnector();

        $filter = [
            '_id' => new MongoDB\BSON\ObjectId($this->game_id),
            'players' => [
                '$elemMatch' => [
                    'id' => (int)$this->id,
                ]
            ]
        ];

        $update = [
            '$set' => [
                'players.$.status' => $status
            ]
        ];

        $mongo->updateData($filter, $update);
    }
    public function get_status() {
        return $this->status;
    }

    public function set_game_id($g_id) {
        $this->game_id = $g_id;
    }
    public function get_game_id() {
        return $this->game_id;
    }

    public function set_nick($nick) {
        $this->nick = $nick;
    }
    public function get_nick() {
        return $this->nick;
    }

    public function getAllData(){
        return $this->id . " | " . $this->nick;
    }
}