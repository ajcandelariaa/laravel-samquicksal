// PREVIEW LOGO
function previewLogo(input){
    var file = $("input[type=file]").get(0).files[0];
    if(file){
        var reader = new FileReader();
        reader.onload = function(){
            $("#restaurantLogo").attr("src", reader.result);
        }
        reader.readAsDataURL(file);
    }
}

// PREVIEW GCASH QR CODE IMAGE
function previewGcashQr(input){
    var file = $("input[type=file]").get(1).files[0];
    if(file){
        var reader = new FileReader();
        reader.onload = function(){
            $("#restaurantGcashQr").attr("src", reader.result);
        }
        reader.readAsDataURL(file);
    }
}

$( "#btn-radiusMin" ).click(function() {
    $("#inputRadius").val(100);
});

$( "#btn-radiusMax" ).click(function() {
    $("#inputRadius").val(10000);
});



// UPDATE TABLES
$('#formUpdateTables').submit(function(e) {
    e.preventDefault();
    Swal.fire({
        title: 'Update Table?',
        text: 'Are you sure you want to update?',
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


// SUBMIT LOCATIOn
$('#locationForm').submit(function(e) {
    e.preventDefault();
    Swal.fire({
      title: 'Submit Location?',
      text: 'After you\'ve submit it you cannot go back here. This is one time only',
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

// DISABLE BUTTONS WHEN CLICKED
$(document).ready(function () {
    $("#contact-form").submit(function (e) {
        $("#btn-contact-submit")
        .removeClass("bg-submitButton hover:bg-btnHoverColor hover:text-gray-300")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});

$(document).ready(function () {
    $("#email-form").submit(function (e) {
        $("#btn-email-submit")
        .removeClass("bg-submitButton hover:bg-btnHoverColor hover:text-gray-300")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});

$(document).ready(function () {
    $("#username-form").submit(function (e) {
        $("#btn-username-submit")
        .removeClass("bg-submitButton hover:bg-btnHoverColor hover:text-gray-300")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});

$(document).ready(function () {
    $("#password-form").submit(function (e) {
        $("#btn-password-submit")
        .removeClass("bg-submitButton hover:bg-btnHoverColor hover:text-gray-300")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});


$(document).ready(function () {
    $("#radius-form").submit(function (e) {
        $("#btn-radius-submit")
        .removeClass("bg-submitButton hover:bg-btnHoverColor hover:text-gray-300")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});

$(document).ready(function () {
    $("#logo-form").submit(function (e) {
        $("#btn-logo-submit")
        .removeClass("bg-submitButton hover:bg-btnHoverColor hover:text-gray-300")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});


$(document).ready(function () {
    $("#qr-form").submit(function (e) {
        $("#btn-qr-submit")
        .removeClass("bg-submitButton hover:bg-btnHoverColor hover:text-gray-300")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});