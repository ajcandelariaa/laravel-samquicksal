// REWARD 1
$("#btn-reward1" ).click(function() {
    document.querySelector(".reward1-form").classList.add("active");
    document.querySelector(".overlay").classList.add("active");
});

$(".overlay").click(function(){
document.querySelector(".reward1-form").classList.remove("active");
document.querySelector(".overlay").classList.remove("active");
});

$(".reward1-form .close-btn").click(function(){
document.querySelector(".reward1-form").classList.remove("active");
document.querySelector(".overlay").classList.remove("active");
});



// REWARD 2
$("#btn-reward2" ).click(function() {
    document.querySelector(".reward2-form").classList.add("active");
    document.querySelector(".overlay").classList.add("active");
});

$(".overlay").click(function(){
document.querySelector(".reward2-form").classList.remove("active");
document.querySelector(".overlay").classList.remove("active");
});

$(".reward2-form .close-btn").click(function(){
document.querySelector(".reward2-form").classList.remove("active");
document.querySelector(".overlay").classList.remove("active");
});


$(document).ready(function () {
    $("#reward1-form").submit(function (e) {
        $("#btn-reward1-submit")
        .removeClass("bg-submitButton hover:bg-darkerSubmitButton hover:text-gray-300")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});

$(document).ready(function () {
    $("#reward2-form").submit(function (e) {
        $("#btn-reward2-submit")
        .removeClass("bg-submitButton hover:bg-darkerSubmitButton hover:text-gray-300")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});