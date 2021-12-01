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