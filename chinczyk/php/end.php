<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' href='../styles/end-style.css'>
    <link rel='icon' href='../imgs/ikona.jpg'>
    <title>Chinol</title>
</head>
<body>

    <div class="container">
        <?php
            session_start();

            if(!isset($_SESSION['player_id']) || !isset($_SESSION['game_id'])){
                header( "Location: ../index.php" );
                exit;
            }

            if(isset($_SESSION['lang'])){
                $lang = $_SESSION['lang'];
            }

            putenv("LC_ALL=$lang");
            setlocale(LC_MESSAGES, "$lang");

            bindtextdomain("end", "../locale");
            textdomain("end");

            $winGT = gettext("Winner");
            $winner = $_POST['winner'];
            $winnerGT = "";
            switch($winner){
                case 'red':
                    $winnerGT = gettext("red");
                    break;
                case 'blue':
                    $winnerGT = gettext("blue");
                    break;
                case 'yellow':
                    $winnerGT = gettext("yellow");
                    break;
                case 'green':
                    $winnerGT = gettext("green");
                    break;
            }

            echo "<h1>$winGT: $winnerGT</h1>";

            $backGT = gettext(("back to main menu"));

            echo "<a href='../'>$backGT</a>";

            session_destroy();

        ?>
    </div>

</body>

</html>