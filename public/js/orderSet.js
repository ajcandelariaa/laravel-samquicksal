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


$('.btn-delete').on('click', function(e){
    e.preventDefault();
    const href = $(this).attr('href')

    Swal.fire({
        title: 'Delete Order Set?',
        text: 'Are you sure you want to delete this order set?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then((result) =>{
        if(result.value){
            document.location.href = href;
        }
    })
});

$('.btn-delete2').on('click', function(e){
    e.preventDefault();
    const href = $(this).attr('href')

    Swal.fire({
        title: 'Delete Food Set?',
        text: 'Are you sure you want to delete this food set in these order set?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then((result) =>{
        if(result.value){
            document.location.href = href;
        }
    })
});

$('.btn-delete3').on('click', function(e){
    e.preventDefault();
    const href = $(this).attr('href')

    Swal.fire({
        title: 'Delete Food Item?',
        text: 'Are you sure you want to delete this food item in these order set?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then((result) =>{
        if(result.value){
            document.location.href = href;
        }
    })
});


$(document).ready(function () {
    $("#orderSet-add-food-set-form").submit(function (e) {
        $("#btn-orderSet-add-food-set")
        .removeClass("bg-submitButton")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});


$(document).ready(function () {
    $("#orderSet-add-food-item-form").submit(function (e) {
        $("#btn-orderSet-add-food-item")
        .removeClass("bg-submitButton")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});


$(document).ready(function () {
    $("#orderSet-edit-form").submit(function (e) {
        $("#btn-add-food-item")
        .removeClass("bg-submitButton")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});