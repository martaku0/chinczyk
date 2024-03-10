import { get_kostka } from "./kostka.js"

var globalTemp = 0;

export function setGlobalTemp(newNum){
    globalTemp = newNum;
}

export function onLoadHandle(){
    let tds = document.querySelectorAll("td")
    let nums = [];
    for(let i = 1; i<=11; i++){
        for(let j = 1; j<=11; j++){
            nums.push(i + "-" + j)
        }
    }
    tds.forEach(function (td, i) {
        td.id = nums[i]
    });

    checkNew();
}

function get_move(url, p, k, pos, act) {

    let formData = new FormData();
    formData.append("pionek", p.id);
    formData.append("kostka", k);
    formData.append("position", pos);
    formData.append("action", act);

    const xhttp = new XMLHttpRequest();
    xhttp.open("POST", url, true);
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4) {
            if (this.status == 200) {
                get_pionkis();
                // console.log('---' + act + "---")
                // console.log(this.responseText)
                try{
                    let json = JSON.parse(this.responseText);
                    let predTd = document.getElementById(json.next);
                    if(act == "predictionOver"){
                        let pionekPred = document.createElement("div");
                        pionekPred.id = `pionek-prediction`;
                        pionekPred.classList.add("pionek");
                        if(document.getElementById('pionek-prediction') == null || document.getElementById('pionek-prediction') == undefined){
                            predTd.appendChild(pionekPred);
                        }
                    }
                    else if(act == "predictionOut"){
                        predTd.removeChild(document.getElementById('pionek-prediction'));
                    }
                    else if(act == "check"){
                        console.log('hhdgajshdjsakhd')
                        console.log(globalTemp)
                        if(json.next == "none"){
                            globalTemp++
                            if(globalTemp == 4 && json.six == "nope"){
                                get_data("../php/nextPlayer.php", null)
                            }
                        }
                    }
                    else if(act == "move"){
                        if(json.next == "end"){
                            // console.log(json);
                            let form = document.createElement("form");
                            form.method = "post";
                            form.action = "end.php";
                            form.style.display = "none";

                            let inp = document.createElement("input");
                            inp.name = "winner";
                            inp.value = `${json.winner}`;

                            form.appendChild(inp);

                            document.body.appendChild(form);
                            form.submit();
                        }
                    }
                }
                catch{
                    // console.log(this.responseText)
                }
            } else {
                // console.error("HTTP request failed with status:", this.status);
            }
        }
    };

    xhttp.onerror = function () {
        // console.error("Network error occurred");
    };

    xhttp.send(formData);
}

function get_playerList(url) {
    const xhttp = new XMLHttpRequest();
    xhttp.open("GET", url, true);
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4) {
            if (this.status == 200) {
                document.getElementsByClassName('playerList')[0].innerHTML = this.responseText;
            } else {
                // console.error("HTTP request failed with status:", this.status);
            }
        }
    };

    xhttp.onerror = function () {
        // console.error("Network error occurred");
    };

    xhttp.send();
}

export function get_data(url, formData) {
    const xhttp = new XMLHttpRequest();
    xhttp.open("POST", url, true);
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4) {
            if (this.status == 200) {
                // console.log(url, this.responseText)
            } else {
                // console.error("HTTP request failed with status:", this.status);
            }
        }
    };

    xhttp.onerror = function () {
        // console.error("Network error occurred");
    };
    
    if (formData !== null) {
        xhttp.send(formData);
    } else {
        xhttp.send();
    }
}

function get_pionkis(url) {
    const xhttp = new XMLHttpRequest();
    xhttp.open("GET", url, true);
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4) {
            if (this.status == 200) {
                try{
                    let json = JSON.parse(this.responseText)
                    clearTds();
                    json.forEach((item) => {
                        let tdId = item.position
                        let color = item.color
                        let id = item.id
                        let td = document.getElementById(tdId);
                        let div_pionek = document.createElement("div");
                        div_pionek.id = `pionek-${color}-${id}`;
                        div_pionek.classList.add("pionek");
                        // td.innerHTML = "";
                        if(td.childElementCount == 2){
                            td.appendChild(document.createElement('br'));
                        }
                        td.appendChild(div_pionek);
                        div_pionek.addEventListener("click", (e)=>{
                            move(e.target, 'move');
                        });
                        div_pionek.addEventListener("mouseover", (e)=>{
                            move(e.target, 'predictionOver');
                        })
                        div_pionek.addEventListener("mouseout", (e)=>{
                            move(e.target, 'predictionOut');
                        })
                    })
                }
                catch(ex){
                    // console.error("JSON parse failed:", ex);
                }
            } else {
                // console.error("HTTP request failed with status:", this.status);
            }
        }
    };

    xhttp.onerror = function () {
        // console.error("Network error occurred");
    };
    xhttp.send();
}

function clearTds(){
    let tds = document.getElementsByClassName('pole');
    let tds_base = document.getElementsByClassName('baza');

    for(let i =0; i<tds.length; i++){
        tds[i].innerHTML = "";
    }
    for(let i =0; i<tds_base.length; i++){
        tds_base[i].innerHTML = "";
    }
}

function get_current(url){
    const xhttp = new XMLHttpRequest();
    xhttp.open("POST", url, true);
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4) {
            if (this.status == 200) {
                // console.log(this.responseText)
                let json = JSON.parse(this.responseText)
                // console.log(json)
                // widoczność przycisku roll
                if(json.afk == "true"){
                    location.href = "../";
                }
                if(json.current_player_id != json.your_player_id || json.current_move != -1){ // 
                    document.getElementById('rzut').style.display = 'none'; 
                }
                else{
                    document.getElementById('rzut').style.display = 'block'; 
                }
                // widoczność przycisku waiting
                if(json.game_status == 1){
                    document.getElementById('changeStatusBtn').style.display = 'none'; 
                }
                else{
                    document.getElementById('changeStatusBtn').style.display = 'block'; 
                }
                //odliczanie do rozpoczecia gry
                if(json.timer != -1){
                    if((json.current_player_id != -1 && json.current_player_id == json.your_player_id) || (json.current_player_id == -1 && json.your_player_id == 0)){
                        url = "../php/timer.php";
                        let formdata = new FormData();
                        formdata.append("action", "playerer")
                        get_data(url, formdata)
                    }
                }

            } else {
                // console.error("HTTP request failed with status:", this.status);
            }

        }
    };

    xhttp.onerror = function () {
        // console.error("Network error occurred");
    };
    xhttp.send();
}

function checkNew(){
    let url = "../php/checkNewPlayers.php";
    get_playerList(url);
    url = "../php/checkPionkis.php";
    get_pionkis(url);
    url = "../php/checkCurrent.php";
    get_kostka(url);
    get_current(url);

    const intervalID1 = setInterval((function(){
        url = "../php/checkNewPlayers.php";
        get_playerList(url);
        url = "../php/checkPionkis.php";
        get_pionkis(url);
        url = "../php/checkCurrent.php";
        get_kostka(url);
        get_current(url);
    }), 1000);
}

export function move(pionek, action){
    let kostka_src = document.getElementById("kostka").src;
    let ilosc = kostka_src.charAt(kostka_src.length - 5);
    let lastTd = pionek.parentElement;
    let lastTd_id = lastTd.id;

    let url = "../php/movePionek.php";

    get_move(url, pionek, ilosc, lastTd_id, action);
}

export function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}
