$(document).ready(function () {
    $("#register2-form").submit(function (e) {
        $("#btn-post-submit")
        .removeClass("bg-submitButton hover:bg-adminLoginTextColor")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});