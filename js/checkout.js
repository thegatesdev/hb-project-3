const calcDisplay = document.getElementById("calc_display");
const keyPadTable = document.getElementById("keypad");
const keyPadKeys = document.getElementsByClassName("keybutton");

let calcDisplayValue = 0;

function clickedKeyPadKey(value){
    if (calcDisplay.value != calcDisplayValue) calcDisplayValue = 0;
    const num = parseInt(value);
    if (num === NaN){
        calcDisplayValue = 0;
    }else{
        calcDisplayValue *= 10
        calcDisplayValue += num;
    }
    calcDisplay.value = calcDisplayValue;
}

for (const key of keyPadKeys) key.onclick = function(){clickedKeyPadKey(key.innerHTML)};