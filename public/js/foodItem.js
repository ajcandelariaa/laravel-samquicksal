// ADD FOOD ITEM FORM
$( "#btn-add-item" ).click(function() {
    document.querySelector(".create-form").classList.add("active");
    document.querySelector(".overlay").classList.add("active");
});

$(".overlay").click(function(){
document.querySelector(".create-form").classList.remove("active");
document.querySelector(".overlay").classList.remove("active");
});

$(".create-form .close-btn").click(function(){
document.querySelector(".create-form").classList.remove("active");
document.querySelector(".overlay").classList.remove("active");
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
        title: 'Delete Food Item?',
        text: 'Are you sure you want to delete this food item?',
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
})



// PARA TO SA STAMP CARD
$('#stampCardForm').submit(function(e) {
    e.preventDefault();
    Swal.fire({
      title: 'Publish Stamp Card?',
      text: 'Are you sure you want publish this stamp card?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes'
    }).then((result) =>{
        if(result.value){
          e.currentTarget.submit();
        }
    });
  });

// FOOD ITEM
$(document).ready(function () {
    $("#food-item").submit(function (e) {
        $("#btn-food-item")
        .removeClass("bg-submitButton hover:bg-darkerSubmitButton hover:text-gray-300")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});

$(document).ready(function () {
    $("#foodItem-edit-form").submit(function (e) {
        $("#btn-add-food-item")
        .removeClass("bg-submitButton hover:bg-darkerSubmitButton hover:text-gray-300")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});


// FOOD SET
$(document).ready(function () {
    $("#foodSet-add-form").submit(function (e) {
        $("#btn-foodSet-add")
        .removeClass("bg-submitButton hover:bg-darkerSubmitButton hover:text-gray-300")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});


// ORDER SET
$(document).ready(function () {
    $("#orderSet-add-form").submit(function (e) {
        $("#btn-orderSet-add")
        .removeClass("bg-submitButton hover:bg-darkerSubmitButton hover:text-gray-300")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});

// DINE IN TIME LIMIT
$(document).ready(function () {
    $("#dine-tl-form").submit(function (e) {
        $("#btn-add-item")
        .removeClass("bg-submitButton hover:bg-darkerSubmitButton hover:text-gray-300")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});

