// ADD HOURS FORM
$( "#btn-add-item" ).click(function() {
    document.querySelector(".create-form").classList.add("active");
    document.querySelector(".overlay").classList.add("active");
});

$(".overlay").click(function(){
document.querySelector(".create-form").classList.remove("active");
document.querySelector(".overlay").classList.remove("active");
});

$(".create-form .close-btn").click(function(){
document.querySelector(".create-form").classList.remove("active");
document.querySelector(".overlay").classList.remove("active");
});

var tempArray = [];

// EDIT HOURS FORM
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

$(".overlay").click(function(){
    document.querySelector(".edit-form").classList.remove("active");
    document.querySelector(".overlay").classList.remove("active");

    $('#updateOpeningTime').val(null)
    $('#updateClosingTime').val(null)
    $('#storeId').val(null)

    for (let i=0; i<tempArray.length; i++){
        if(i != 0 && i != 1 && i != 2){
            switch (tempArray[i]){
                case 'SU':
                    $('#SU').prop('disabled', true);
                    $('#SU').prop('checked', false)
                    break;
                case 'MO':
                    $('#MO').prop('disabled', true);
                    $('#MO').prop('checked', false)
                    break;
                case 'TU':
                    $('#TU').prop('disabled', true);
                    $('#TU').prop('checked', false)
                    break;
                case 'WE':
                    $('#WE').prop('disabled', true);
                    $('#WE').prop('checked', false)
                    break;
                case 'TH':
                    $('#TH').prop('disabled', true);
                    $('#TH').prop('checked', false)
                    break;
                case 'FR':
                    $('#FR').prop('disabled', true);
                    $('#FR').prop('checked', false)
                    break;
                case 'SA':
                    $('#SA').prop('disabled', true);
                    $('#SA').prop('checked', false)
                    break;
                default:
            }
        }
    }
});

$(".edit-form .close-btn").click(function(){
    document.querySelector(".edit-form").classList.remove("active");
    document.querySelector(".overlay").classList.remove("active");
    
    $('#updateOpeningTime').val(null)
    $('#updateClosingTime').val(null)
    $('#storeId').val(null)

    for (let i=0; i<tempArray.length; i++){
        if(i != 0 && i != 1 && i != 2){
            switch (tempArray[i]){
                case 'SU':
                    $('#SU').prop('disabled', true);
                    $('#SU').prop('checked', false)
                    break;
                case 'MO':
                    $('#MO').prop('disabled', true);
                    $('#MO').prop('checked', false)
                    break;
                case 'TU':
                    $('#TU').prop('disabled', true);
                    $('#TU').prop('checked', false)
                    break;
                case 'WE':
                    $('#WE').prop('disabled', true);
                    $('#WE').prop('checked', false)
                    break;
                case 'TH':
                    $('#TH').prop('disabled', true);
                    $('#TH').prop('checked', false)
                    break;
                case 'FR':
                    $('#FR').prop('disabled', true);
                    $('#FR').prop('checked', false)
                    break;
                case 'SA':
                    $('#SA').prop('disabled', true);
                    $('#SA').prop('checked', false)
                    break;
                default:
            }
        }
    }
});