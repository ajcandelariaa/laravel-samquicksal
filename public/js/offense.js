function cancellationBlockDays() {
    var offenseType = document.getElementById("offenseTypeC");
    var selectedValue = offenseType.options[offenseType.selectedIndex].value;
    if (selectedValue == "limitBlock") {
        $('#showCancellationBlockDays').show();
    } else {
        $('#showCancellationBlockDays').hide();
        $('#showCancellationBlockDays').val("");
    }
}

function noShowBlockDays() {
    var offenseType = document.getElementById("offenseTypeN");
    var selectedValue = offenseType.options[offenseType.selectedIndex].value;
    if (selectedValue == "limitBlock") {
        $('#showNoShowBlockDays').show();
    } else {
        $('#showNoShowBlockDays').hide();
        $('#showNoShowBlockDays').val("");
    }
}

function runawayBlockDay() {
    var offenseType = document.getElementById("offenseTypeR");
    var selectedValue = offenseType.options[offenseType.selectedIndex].value;
    if (selectedValue == "limitBlock") {
        $('#showRunawayBlockDays').show();
    } else {
        $('#showRunawayBlockDays').hide();
        $('#showRunawayBlockDays').val("");
    }
}