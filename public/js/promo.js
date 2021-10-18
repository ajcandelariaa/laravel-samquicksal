// ADD MECHANICS
function add_row_ingredient(){
    var rowno = $("#ingredients_table tr").length;
    rowno += 1;

    $("#ingredients_table tr:last").after(`
        <tr id='ingr_row`+rowno+`'>
            <td style="width:90%;"><textarea name="promoMechanics[]" class="w-full border border-gray-400 py-1 px-2 text-sm text-gray-700 focus:outline-none focus:border-black" placeholder="Enter Mechanics" rows="1"></textarea></td>
            <td style="width:10%; padding-left: 5px;"><button class="mt-2 text-red-700 text-lg" onclick="delete_row_ingredient('ingr_row`+rowno+`')"><i class="far fa-trash-alt"></i></button></td>
        </tr>
    `);
}
function delete_row_ingredient(rowno){
    $('#'+rowno).remove();
}

// EDIT MECHANICS
function add_row_ingredient_edit(){
    var rowno = $("#ingredients_table tr").length;
    rowno += 1;

    $("#ingredients_table tr:last").after(`
        <tr id='ingr_row`+rowno+`'>
            <td style="width:90%;"><textarea name="promoMechanicsEdit[]" class="w-full border border-gray-400 py-1 px-2 text-sm text-gray-700 focus:outline-none focus:border-black" placeholder="Enter Mechanics" rows="1"></textarea></td>
            <td style="width:10%; padding-left: 5px;"><button class="mt-2 text-red-700 text-lg" onclick="delete_row_ingredient_edit('ingr_row`+rowno+`')"><i class="far fa-trash-alt"></i></button></td>
        </tr>
    `);
}
function delete_row_ingredient_edit(rowno){
    $('#'+rowno).remove();
}



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
        title: 'Delete Promo?',
        text: 'Are you sure you want to delete this promo?',
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