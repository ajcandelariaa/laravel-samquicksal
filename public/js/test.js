// RESTAURANT DECLINED FORM
$( "#btn-declined" ).click(function() {
  document.querySelector(".popup").classList.add("active");
  document.querySelector(".overlay").classList.add("active");
});

$(".overlay").click(function(){
  document.querySelector(".popup").classList.remove("active");
  document.querySelector(".overlay").classList.remove("active");
});

$(".popup .close-btn").click(function(){
  document.querySelector(".popup").classList.remove("active");
  document.querySelector(".overlay").classList.remove("active");
});

$('#declinedForm').submit(function(e) {
  e.preventDefault();
  Swal.fire({
    title: 'Form Decline?',
    text: 'Are you sure you want to decline this form?',
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

$('#approvedForm').submit(function(e) {
  e.preventDefault();
  Swal.fire({
    title: 'Form Approved?',
    text: 'Are you sure you want to approve this applicant?',
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


// RESTAURANT DELETE FORM
$('#deleteForm').on('click', function(e){
    e.preventDefault();
    const href = $(this).attr('href')

    Swal.fire({
        title: 'Form Delete?',
        text: 'Are you sure you want to delete this form?',
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