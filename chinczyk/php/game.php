<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chinol</title>
    <link rel="stylesheet" href="../styles/game-style.css">
    <link rel="icon" href="../imgs/ikona.jpg">
</head>

<body>
    <div id="container">
        <div class="playerList">
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

                bindtextdomain("game", "../locale");
                textdomain("game");

                $gameidGT = gettext("GAME ID");

                if(isset($_SESSION['player_id']) && isset($_SESSION['game_id'])){
                    echo "<p class='game_id_info'>$gameidGT: <span>".$_SESSION['game_id']."</span></p>";

                    require_once("mongoConnectorClass.php");

                    $mongo = new MongoConnector();
                    
                    $filter = ["_id" => $_SESSION['game_id']];
    
                    $data = $mongo->getData($filter)[0];
    
                    $players = $data->players;

                    echo "<div class='players'>";
                    for($i = 0; $i<4; $i++){
                        if(isset($players[$i])){
                            echo "<div style='background-color:lightgray'>".$players[$i]->nick."</div>";
                        }
                        else{
                            echo "<div style='background-color:lightgray'></div>";
                        }
                    }
                    echo "</div>";

                    $timer = $data->current->timer;

                    if($timer >= 0){
                        echo "<p id='timer'>$timer</p>";
                    }
                }
            ?>
        </div>
        <?php
            session_start();

            if(isset($_SESSION['player_id']) && isset($_SESSION['game_id'])){
                require_once('mongoConnectorClass.php');
                $mongo = new MongoConnector();
                $filter = [
                    '_id' => new MongoDB\BSON\ObjectId($_SESSION['game_id'])
                ];            
                $res = $mongo->getData($filter)[0];

                $p_id = (int)$_SESSION['player_id'];

                $status = $res->players[$p_id]->status;

                $lang = $_SESSION['lang'];

                putenv("LC_ALL=$lang");
                setlocale(LC_MESSAGES, "$lang");

                bindtextdomain("game", "../locale");
                textdomain("game");

                $readyGT = gettext('ready to play');
                $waitGT = gettext('waiting for others');

                if($status == 0){
                    echo "<input style='display:none;' type='button' id='changeStatusBtn' name='status' value='$readyGT'>";
                }
                else{
                    echo "<input style='display:none;' type='button' id='changeStatusBtn' name='status' value='$waitGT'>";
                }
            }
        ?>
        <div class="main">
            <div id="kostka-container">
                <p id="infoP"></p>
                <img id="kostka" src="../imgs/kostka-1.png">
                <button style='display:none;' id="rzut">
                <?php
                    $lang = $_SESSION['lang'];

                    putenv("LC_ALL=$lang");
                    setlocale(LC_MESSAGES, "$lang");
            
                    bindtextdomain("game", "../locale");
                    textdomain("game");

                    echo gettext("Roll");
                ?>
                </button>
            </div>
            <div id="plansza-container">
                <table id="plansza">
                    <!--1--><tr>
                        <td class="baza red">
                        </td>
                        <td class="baza red">
                        </td>
                        <td></td>
                        <td></td>
                        <td class="pole">
                        </td>
                        <td class="pole"></td>
                        <td class="pole blue-main"></td>
                        <td></td>
                        <td></td>
                        <td class="baza blue">
                        </td>
                        <td class="baza blue">
                        </td>
                    </tr>
                    <!--2--><tr>
                        <td class="baza red">
                        </td>
                        <td class="baza red">
                        </td>
                        <td></td>
                        <td></td>
                        <td class="pole"></td>
                        <td class="pole blue-box"></td>
                        <td class="pole"></td>
                        <td></td>
                        <td></td>
                        <td class="baza blue">
                        </td>
                        <td class="baza blue">
                        </td>
                    </tr>
                    <!--3--><tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="pole"></td>
                        <td class="pole blue-box"></td>
                        <td class="pole"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <!--4--><tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="pole"></td>
                        <td class="pole blue-box"></td>
                        <td class="pole"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <!--5--><tr>
                        <td class="pole red-main"></td>
                        <td class="pole"></td>
                        <td class="pole"></td>
                        <td class="pole"></td>
                        <td class="pole"></td>
                        <td class="pole blue-box"></td>
                        <td class="pole"></td>
                        <td class="pole"></td>
                        <td class="pole"></td>
                        <td class="pole"></td>
                        <td class="pole"></td>
                    </tr>
                    <!--6--><tr>
                        <td class="pole"></td>
                        <td class="pole red-box"></td>
                        <td class="pole red-box"></td>
                        <td class="pole red-box"></td>
                        <td class="pole red-box"></td>
                        <td><img src="../imgs/star.png"></td>
                        <td class="pole green-box"></td>
                        <td class="pole green-box"></td>
                        <td class="pole green-box"></td>
                        <td class="pole green-box"></td>
                        <td class="pole"></td>
                    </tr>
                    <!--7--><tr>
                        <td class="pole"></td>
                        <td class="pole"></td>
                        <td class="pole"></td>
                        <td class="pole"></td>
                        <td class="pole"></td>
                        <td class="pole yellow-box"></td>
                        <td class="pole"></td>
                        <td class="pole"></td>
                        <td class="pole"></td>
                        <td class="pole"></td>
                        <td class="pole green-main"></td>
                    </tr>
                    <!--8--><tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="pole"></td>
                        <td class="pole yellow-box"></td>
                        <td class="pole"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <!--9--><tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="pole"></td>
                        <td class="pole yellow-box"></td>
                        <td class="pole"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <!--10--><tr>
                        <td class="baza yellow">
                        </td>
                        <td class="baza yellow">
                        </td>
                        <td></td>
                        <td></td>
                        <td class="pole"></td>
                        <td class="pole yellow-box"></td>
                        <td class="pole"></td>
                        <td></td>
                        <td></td>
                        <td class="baza green">
                        </td>
                        <td class="baza green">
                        </td>
                    </tr>
                    <!--11--><tr>
                        <td class="baza yellow">
                        </td>
                        <td class="baza yellow">
                        </td>
                        <td></td>
                        <td></td>
                        <td class="pole yellow-main"></td>
                        <td class="pole"></td>
                        <td class="pole"></td>
                        <td></td>
                        <td></td>
                        <td class="baza green">
                        </td>
                        <td class="baza green">
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <script src="../scripts/kostka.js" type='module' defer></script>
    <script src="../scripts/pionki_gracze.js" type='module' defer></script>
    <script src="../scripts/main.js" type='module' defer></script>
</body>
</html>