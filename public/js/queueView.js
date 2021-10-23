// DECLINED CUSTOMER
$( "#btn-decline" ).click(function() {
    document.querySelector(".declined-form").classList.add("active");
    document.querySelector(".overlay").classList.add("active");
});

$(".overlay").click(function(){
    document.querySelector(".declined-form").classList.remove("active");
    document.querySelector(".overlay").classList.remove("active");
});

$(".declined-form .close-btn").click(function(){
    document.querySelector(".declined-form").classList.remove("active");
    document.querySelector(".overlay").classList.remove("active");
});

// DECLINED CONFIRMATION
$('#declinedForm').submit(function(e) {
    e.preventDefault();
    Swal.fire({
        title: 'Decline?',
        text: 'Are you sure you want to decline this customer?',
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


// APPROVED CONFIRMATION
$('.btn-approve').on('click', function(e){
    e.preventDefault();
    const href = $(this).attr('href')

    Swal.fire({
        title: 'Approve Customer?',
        text: 'Are you sure you want to approve this customer?',
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