import { onLoadHandle, get_data } from './pionki_gracze.js'
import { rzutKostka } from "./kostka.js"

window.onload = function(){
    onLoadHandle()
    document.getElementById("rzut").addEventListener('click', ()=>{rzutKostka()})
    document.getElementById("changeStatusBtn").addEventListener('click', (e)=>{
        let act_val = e.target.value

        let formData = new FormData();
        formData.append('status', act_val);
        get_status("../php/changePlayerStatus.php", formData, e);
    })
};

export function get_status(url, formData, e) {
    const xhttp = new XMLHttpRequest();
    xhttp.open("POST", url, true);
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4) {
            if (this.status == 200) {
                // console.log(this.responseText)
                e.target.value = this.responseText;
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