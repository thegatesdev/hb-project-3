const calcDisplay = document.getElementById("calc_display");
const keyPadTable = document.getElementById("keypad");
const keyPadKeys = document.getElementsByClassName("keybutton");
const selectedProd = document.getElementById("selected_prod_input");

let calcDisplayValue = 0;

let selectedElement = null;

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


function beforeSubmit(){
    selectedProd.value = selectedElement;
}

$("#prodlist tr").slice(1).click(function(){
    $(this).addClass('selected').siblings().removeClass('selected');    
    selectedElement = $(this).index();
});