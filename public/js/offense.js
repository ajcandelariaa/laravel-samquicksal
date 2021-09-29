function cancellationBlockDays() {
    var offenseType = document.getElementById("offenseType");
    var selectedValue = offenseType.options[offenseType.selectedIndex].value;
    if (selectedValue == "limitBlock") {
        $('#showCancellationBlockDays').show();
    } else {
        $('#showCancellationBlockDays').hide();
        $('#showCancellationBlockDays').val("");
    }
}

function noShowBlockDays() {
    var offenseType = document.getElementById("offenseType");
    var selectedValue = offenseType.options[offenseType.selectedIndex].value;
    if (selectedValue == "limitBlock") {
        $('#showCancellationBlockDays').show();
    } else {
        $('#showCancellationBlockDays').hide();
        $('#showCancellationBlockDays').val("");
    }
}

function runawayBlockDays() {
    var offenseType = document.getElementById("offenseType");
    var selectedValue = offenseType.options[offenseType.selectedIndex].value;
    if (selectedValue == "limitBlock") {
        $('#showCancellationBlockDays').show();
    } else {
        $('#showCancellationBlockDays').hide();
        $('#showCancellationBlockDays').val("");
    }
}