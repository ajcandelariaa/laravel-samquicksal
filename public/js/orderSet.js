// ADD FOOD ITEM TO ORDER SET
$( "#btn-add-food-item" ).click(function() {
    document.querySelector(".add-form").classList.add("active");
    document.querySelector(".overlay").classList.add("active");
});

$(".overlay").click(function(){
document.querySelector(".add-form").classList.remove("active");
document.querySelector(".overlay").classList.remove("active");
});


// ADD FOOD SET TO ORDER SET
$( "#btn-add-food-set" ).click(function() {
    document.querySelector(".add-form2").classList.add("active");
    document.querySelector(".overlay").classList.add("active");
});

$(".overlay").click(function(){
document.querySelector(".add-form2").classList.remove("active");
document.querySelector(".overlay").classList.remove("active");
});


// SELCET ALL FOOD SET
$(document).ready(function() {
    $('#select-all-food-set').click(function() {
        $('.foodSet').prop('checked', true);
    })
});

// SELCET ALL FOOD ITEM
$(document).ready(function() {
    $('#select-all-food-item').click(function() {
        $('.foodItem').prop('checked', true);
    })
});

// PREVIEW IMAGE
function previewFile(input){
    var file = $("input[type=file]").get(0).files[0];
    if(file){
        var reader = new FileReader();
        reader.onload = function(){
            $("#previewImg").attr("src", reader.result);
        }
        reader.readAsDataURL(file);
    }
}