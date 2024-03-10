function validateForm() {
    let nickInput = document.getElementById('nick-inp');
    let nickValue = nickInput.value.trim();
    let regex = /^[a-zA-Z0-9]+$/; //only letters (uppercase and lowercase) and numbers
    if (nickValue === '') {
        nickInput.style.border = "3px solid red";
        return false;
    } else if (!regex.test(nickValue)) {
        nickInput.style.border = "3px solid red";
        return false;
    }
    
    return true;
}
