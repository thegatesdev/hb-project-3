const selectedProd = document.getElementById("selected_prod_input");

let selectedElement = null;

$("#product_table tr").slice(1).click(function(){
    $(this).addClass('selected').siblings().removeClass('selected');    
    selectedElement = $(this).index();
});

function beforeSubmit(){
    selectedProd.value = selectedElement;
}