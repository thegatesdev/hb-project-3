const selectedProd = document.getElementById("selected_prod_input");
const containerForm = document.getElementById("container");
const table = document.getElementById("product_table");

$("#product_table tr").slice(1).click(function(){
    selectedProd.value = table.rows[$(this).index()].cells[0].innerText;
    containerForm.submit();
});