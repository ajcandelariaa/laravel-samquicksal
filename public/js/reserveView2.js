// VALIDATE CUSTOMER
$('.btn-validate').on('click', function(e){
    e.preventDefault();
    const href = $(this).attr('href')

    Swal.fire({
        title: 'Validate Customer?',
        text: 'Are you sure you want to validate this customer?',
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


// NO SHOW CUSTOMER
$('.btn-no-show').on('click', function(e){
    e.preventDefault();
    const href = $(this).attr('href')

    Swal.fire({
        title: 'Customer No show?',
        text: 'Are you sure you want to do this action?',
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


// ADMIT CUSTOMER
$(document).ready(function(){
    $('#inputTableNum').on('keyup',function(e) {
        $(this).val($(this).val().replace(/ /g, ""));
    });
});
$( "#btn-admit" ).click(function() {
    document.querySelector(".admit-form").classList.add("active");
    document.querySelector(".overlay").classList.add("active");
});

$(".overlay").click(function(){
    document.querySelector(".admit-form").classList.remove("active");
    document.querySelector(".overlay").classList.remove("active");
});

$(".admit-form .close-btn").click(function(){
    document.querySelector(".admit-form").classList.remove("active");
    document.querySelector(".overlay").classList.remove("active");
});


$('#admitForm').submit(function(e) {
    e.preventDefault();
    Swal.fire({
        title: 'Admit Customer?',
        text: 'Are you sure you want to admit this customer?',
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