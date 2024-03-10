import { sleep, move, setGlobalTemp } from "./pionki_gracze.js"

export async function rzutKostka(){
    let kostka = document.getElementById("kostka")
    let rand = getRandomInt(1,6)
    // for(let i = 0; i<30; i++){
    //     rand = getRandomInt(1,6)
    //     kostka.src = "../imgs/kostka-" + rand + ".png";
    //     await sleep(80);
    // }

    setGlobalTemp(0);

	let formData = new FormData();
	formData.append('rzut', rand);

	rzut_kostka("../php/rollKostka.php", formData);

    speak(rand)

    let pionkis = document.getElementsByClassName("pionek");
    for(let i =0; i<pionkis.length; i++){
        if(pionkis[i].id != "pionek-prediction"){
            move(pionkis[i], 'check')
        }
    }
}

function getRandomInt(min, max){
    return Math.floor(Math.random() * max) + min;
}

let voices = [];
let utterance = [];

function populateVoiceList() {
	// let utterance = new SpeechSynthesisUtterance("Witaj świecie!");
	const synth = window.speechSynthesis;
	voices = synth.getVoices();
	// "Chrome, at least, used to permit speech by default without user interaction, but that changed"
	// dodałem buttona by to przetestować
};

function speak(number) {
	utterance = new SpeechSynthesisUtterance(number);

	utterance.voice = voices[3];
	utterance.pitch = 1; // 0-2 (1 default)
	utterance.rate = 1; // 1 default, 0.5 - o połowę wolniej, 2 - dwa razy szybciej, itd.

    get_lang("../php/changeLanguage.php")
    
	// console.log(utterance)
}

// chrome/safari - onvoicechanged, firefox - nie
populateVoiceList();
if (speechSynthesis.onvoiceschanged !== undefined) {
	speechSynthesis.onvoiceschanged = populateVoiceList;
}

export function get_kostka(url) {
    const xhttp = new XMLHttpRequest();
    xhttp.open("POST", url, true);
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4) {
            if (this.status == 200) {
                try{
                    let last_move = JSON.parse(this.responseText)['last_move']
                    if(last_move > 0 && last_move < 7){
                        let kostka = document.getElementById("kostka")
                        kostka.src = "../imgs/kostka-" + last_move + ".png";
                    }
                }
				catch(ex){
                    // console.log(ex);
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

function rzut_kostka(url, formData){
	const xhttp = new XMLHttpRequest();
    xhttp.open("POST", url, true);
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4) {
            if (this.status == 200) {
				get_kostka("../php/checkCurrent.php");
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

function get_lang(url) {
    const xhttp = new XMLHttpRequest();
    xhttp.open("POST", url, true);
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4) {
            if (this.status == 200) {
                if(this.responseText != ""){
                    let isVoice = false;
                    let lang_ = this.responseText.replace('_', '-')
                    // console.log(voices);
                    for(let i =0; i<voices.length; i++){
                        if(lang_ == voices[i].lang){
                            utterance.voice = voices[i];
                            isVoice = true;
                            break;
                        }
                    }
                    if(!isVoice){
                        let default_voice = "";
                        for(let i =0; i<voices.length; i++){
                            if("en-GB" == voices[i].lang || "en-US" == voices[i].lang){
                                utterance.voice = voices[i];
                                default_voice = voices[i].lang
                                break;
                            }
                        }
                        document.getElementById("infoP").innerText = `no speaker in your language (${lang_})\nyou will hear ${default_voice}`;
                    }
	                speechSynthesis.speak(utterance);

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
