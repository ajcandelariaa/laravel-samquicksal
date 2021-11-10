$(".btn-edit-item" ).click(function() {
    document.querySelector(".edit-form").classList.add("active");
    document.querySelector(".overlay").classList.add("active");

    $tr = $(this).closest('.tr');
    
    var data = $tr.children('.td').map(function(){
        return $(this).text();
    }).get();

    $('#updateOpeningTime').val(data[0])
    $('#updateClosingTime').val(data[1])
    $('#storeId').val(data[2])

    for (let i=0; i<data.length; i++){
        tempArray.push(data[i])
        if(i != 0 && i != 1 && i != 2){
            switch (data[i]){
                case 'SU':
                    $('#SU').prop('disabled', false);
                    $('#SU').prop('checked', true)
                    break;
                case 'MO':
                    $('#MO').prop('disabled', false);
                    $('#MO').prop('checked', true)
                    break;
                case 'TU':
                    $('#TU').prop('disabled', false);
                    $('#TU').prop('checked', true)
                    break;
                case 'WE':
                    $('#WE').prop('disabled', false);
                    $('#WE').prop('checked', true)
                    break;
                case 'TH':
                    $('#TH').prop('disabled', false);
                    $('#TH').prop('checked', true)
                    break;
                case 'FR':
                    $('#FR').prop('disabled', false);
                    $('#FR').prop('checked', true)
                    break;
                case 'SA':
                    $('#SA').prop('disabled', false);
                    $('#SA').prop('checked', true)
                    break;
                default:
            }
        }
    }
});










// GRANT ACCESS CONFIRMATION
$('#btn-access').on('click', function(e){
    e.preventDefault();
    const href = $(this).attr('href')

    Swal.fire({
        title: 'Grant Access?',
        text: 'Are you sure you want to grant access to this customer?',
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


$(document).ready(function() {
    $('#select-all').click(function() {
        var checked = this.checked;
        $('input[type="checkbox"]').each(function() {
        this.checked = checked;
    });
    })
});

// SERVE CUSTOMER
$('#serveForm').submit(function(e) {
    e.preventDefault();
    Swal.fire({
        title: 'Done Serving?',
        text: 'Are you done serving these food to the customer?',
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


// RUNAWAY CUSTOMER
$('#btn-runaway').on('click', function(e){
    e.preventDefault();
    const href = $(this).attr('href')

    Swal.fire({
        title: 'Runaway?',
        text: 'Are you sure this customer did not pay?',
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


// COMPLETE CUSTOMER
$('#btn-complete').on('click', function(e){
    e.preventDefault();
    const href = $(this).attr('href')

    Swal.fire({
        title: 'End Service?',
        text: 'Are you sure you are done serving this customer?',
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

// Invalid Receipt
$('#btn-insAmount').on('click', function(e){
    e.preventDefault();
    const href = $(this).attr('href')

    Swal.fire({
        title: 'Inssuficient Amount?',
        text: 'Are you sure this is inssuficient amount?',
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

// Invalid Receipt
$('#btn-inReceipt').on('click', function(e){
    e.preventDefault();
    const href = $(this).attr('href')

    Swal.fire({
        title: 'Invalid Receipt?',
        text: 'Are you sure gcash receipt is invalid?',
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