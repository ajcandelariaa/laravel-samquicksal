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


$(document).ready(function () {
    $("#cancel-offense-form").submit(function (e) {
        $("#btn-cancel-offense")
        .removeClass("bg-submitButton hover:bg-btnHoverColor")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});

$(document).ready(function () {
    $("#noShow-offense-form").submit(function (e) {
        $("#btn-noShow-offense")
        .removeClass("bg-submitButton hover:bg-btnHoverColor")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});

$(document).ready(function () {
    $("#runaway-offense-form").submit(function (e) {
        $("#btn-runaway-offense")
        .removeClass("bg-submitButton hover:bg-btnHoverColor")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});