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