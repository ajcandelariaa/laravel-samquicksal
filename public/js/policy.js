// EDIT HOURS FORM
$(".btn-edit-item" ).click(function() {
    document.querySelector(".edit-form").classList.add("active");
    document.querySelector(".overlay").classList.add("active");
    $tr = $(this).closest('.tr');
    var data = $tr.children('.td').map(function(){
        return $(this).text();
    }).get();
    $('#updatePolicyId').val(data[0])
    $('#updatePolicyDesc').val(data[1])
});

$(".overlay").click(function(){
    document.querySelector(".edit-form").classList.remove("active");
    document.querySelector(".overlay").classList.remove("active");
    $('#updatePolicyId').val(null)
    $('#updatePolicyDesc').val(null)
});

$(".edit-form .close-btn").click(function(){
    document.querySelector(".edit-form").classList.remove("active");
    document.querySelector(".overlay").classList.remove("active");
    $('#updatePolicyId').val(null)
    $('#updatePolicyDesc').val(null)
});


$('.btn-delete').on('click', function(e){
    e.preventDefault();
    const href = $(this).attr('href')

    Swal.fire({
        title: 'Delete policy?',
        text: 'Are you sure you want to delete this policy?',
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