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

// APPROVED CUSTOMER
$( "#btn-approved" ).click(function() {
    document.querySelector(".approved-form").classList.add("active");
    document.querySelector(".overlay").classList.add("active");
});

$(".overlay").click(function(){
    document.querySelector(".approved-form").classList.remove("active");
    document.querySelector(".overlay").classList.remove("active");
});

$(".approved-form .close-btn").click(function(){
    document.querySelector(".approved-form").classList.remove("active");
    document.querySelector(".overlay").classList.remove("active");
});

// APPROVED CONFIRMATION
$('#approvedForm').submit(function(e) {
    e.preventDefault();
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
          e.currentTarget.submit();
        }
    });
});


//  CHANGE NUMBER OF TABLE 2
$( "#tableType" ).on('change', function() {
    noOfPersons = $('#noOfPersons').val();
    tableType = $('#tableType').val();
    noOfPersons = noOfPersons.replace(/^0+/, '');
    $('#noOfPersons').val(noOfPersons)

    if(noOfPersons == 0){
        $('#noOfTables').val(0)
    } else if(parseInt(noOfPersons) <= parseInt(tableType)){
        $('#noOfTables').val(1)
    } else {
        var x = parseInt(noOfPersons / tableType)
        var remainder = noOfPersons % tableType
        if(remainder > 0){
            x += 1
        }
        $('#noOfTables').val(x)
    }
});


// DECLINED FORM
$(document).ready(function () {
    $("#declinedForm").submit(function (e) {
        $("#btn-declined-form")
        .removeClass("bg-submitButton hover:bg-darkerSubmitButton hover:text-gray-300")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});

// APPROVED FORM
$(document).ready(function () {
    $("#approvedForm").submit(function (e) {
        $("#btn-approved-form")
        .removeClass("bg-submitButton hover:bg-darkerSubmitButton hover:text-gray-300")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});