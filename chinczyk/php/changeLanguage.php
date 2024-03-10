<?php

session_start();

if(isset($_POST['lang'])){

    $_SESSION['lang'] = $_POST['lang'];

    header( "Location: ../index.php" );
    exit;
}

if(isset($_SESSION["lang"])){
    $lang = $_SESSION['lang'];
    echo "$lang";
}
        
