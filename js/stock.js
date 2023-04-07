const selectedProd = document.getElementById("selected_prod_input");
const containerForm = document.getElementById("container");

$("#product_table tr").slice(1).click(function(){
    selectedProd.value = $(this).index();
    containerForm.submit();
});