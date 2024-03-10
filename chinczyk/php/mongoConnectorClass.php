<?php

class MongoConnector{
    private $manager;

    public function __construct() {
        include("data.php");
        try {
            $this->manager = new MongoDB\Driver\Manager($mongo_str);
            // echo "Connection to database successfully";
        }
        catch (Throwable $e) {
            echo "Captured Throwable for connection : " . $e->getMessage() . PHP_EOL;
        }
    }

    public function insertData($data){
        include("data.php");
        $bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);

        $bulk->insert($data);

        $this->manager->executeBulkWrite($mongo_db, $bulk);
    }

    public function updateData($filter, $option){
        include("data.php");
        $bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);

        $bulk->update($filter, $option);

        $this->manager->executeBulkWrite($mongo_db, $bulk);
    }

    public function getData($filter){
        include("data.php");

        $query = new MongoDB\Driver\Query($filter);

        $result = $this->manager->executeQuery($mongo_db, $query);

        return $result->toArray();
    }

    public function clearDb(){
        include("data.php");
        $bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);

        $bulk->delete([]);

        $this->manager->executeBulkWrite($mongo_db, $bulk);
    }
}