// ADD FOOD ITEM TO FOOD SET
$( "#btn-add-food-item" ).click(function() {
    document.querySelector(".add-form").classList.add("active");
    document.querySelector(".overlay").classList.add("active");
});

$(".overlay").click(function(){
document.querySelector(".add-form").classList.remove("active");
document.querySelector(".overlay").classList.remove("active");
});


// SELCET ALL
$(document).ready(function() {
    $('#select-all').click(function() {
        var checked = this.checked;
        $('input[type="checkbox"]').each(function() {
        this.checked = checked;
    });
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
        title: 'Delete Food Set?',
        text: 'Are you sure you want to delete this food set?',
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
        title: 'Delete Food Item?',
        text: 'Are you sure you want to delete this food item in these food set?',
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
    $("#foodSet-add-fi-form").submit(function (e) {
        $("#btn-foodSet-add-fi")
        .removeClass("bg-submitButton")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});


$(document).ready(function () {
    $("#foodSet-edit-form").submit(function (e) {
        $("#btn-add-food-item")
        .removeClass("bg-submitButton")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});