<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' href='./styles/index-style.css'>
    <link rel='icon' href='./imgs/ikona.jpg'>
    <title>Chinol</title>
</head>
<body>

    <?php
        session_start();

        if(isset($_SESSION['player_id']) && isset($_SESSION['game_id'])){
            header( 'Location: php/game.php' );
            exit;
        }

        $lang = "en_GB";
        if(isset($_SESSION['lang'])){
            $lang = $_SESSION['lang'];
        }
        else{
            $_SESSION['lang'] = "en_GB";
        }

        putenv("LC_ALL=$lang");
        setlocale(LC_MESSAGES, '$lang');

        bindtextdomain("index", "./locale");
        textdomain("index");

        $h1GT = gettext("Little chinese man");
        $inpGT = gettext("Play");
        $lblGT = gettext("Nick");
        $selGT = gettext("Change");

        $isEnDefault = "";
        $isPlDefault = "";
        $isDeDefault = "";

        if($lang=="pl_PL"){
            $isPlDefault = "selected";
        }
        else if($lang=="en_GB"){
            $isEnDefault = "selected";
        }

        echo "
        <form action='php/changeLanguage.php' method='POST'>
            <select name='lang'>
                <option value='en_GB' $isEnDefault>English</option>
                <option value='pl_PL' $isPlDefault>Polski</option>
                <option value='de_DE' $isDeDefault>Deutsch</option>
            </select>
            <input type='submit' value='$selGT'>
        </form>
        <h1 style='margin: 50px;'>$h1GT</h1>
        <form id='nickForm' action='php/login.php' method='POST' name='nickForm' onsubmit='return validateForm()'>
            <label>$lblGT [a-zA-Z0-9]: <input type='text' name='nick' id='nick-inp'></label><br><br>
            <input type='submit' name='submit' id='submit-btn' value='$inpGT' maxlength='12'>
        </form>";

    ?>
    <script src="scripts/index.js" defer></script>
    
</body>

</html>