<?php

require_once("mongoConnectorClass.php");

$mongo = new MongoConnector();

$mongo->clearDb();

session_start();
session_destroy();

header("Location: ../");
exit;