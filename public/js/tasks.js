// TASK 1
$("#btn-task1" ).click(function() {
    document.querySelector(".task1-form").classList.add("active");
    document.querySelector(".overlay").classList.add("active");
});

$(".overlay").click(function(){
document.querySelector(".task1-form").classList.remove("active");
document.querySelector(".overlay").classList.remove("active");
});

$(".task1-form .close-btn").click(function(){
document.querySelector(".task1-form").classList.remove("active");
document.querySelector(".overlay").classList.remove("active");
});



// TASK 2
$("#btn-task2" ).click(function() {
    document.querySelector(".task2-form").classList.add("active");
    document.querySelector(".overlay").classList.add("active");
});

$(".overlay").click(function(){
document.querySelector(".task2-form").classList.remove("active");
document.querySelector(".overlay").classList.remove("active");
});

$(".task2-form .close-btn").click(function(){
document.querySelector(".task2-form").classList.remove("active");
document.querySelector(".overlay").classList.remove("active");
});



// TASK 3
$("#btn-task3" ).click(function() {
    document.querySelector(".task3-form").classList.add("active");
    document.querySelector(".overlay").classList.add("active");
});

$(".overlay").click(function(){
document.querySelector(".task3-form").classList.remove("active");
document.querySelector(".overlay").classList.remove("active");
});

$(".task3-form .close-btn").click(function(){
document.querySelector(".task3-form").classList.remove("active");
document.querySelector(".overlay").classList.remove("active");
});



$(document).ready(function () {
    $("#task1-form").submit(function (e) {
        $("#btn-task1-submit")
        .removeClass("bg-submitButton hover:bg-darkerSubmitButton hover:text-gray-300")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});

$(document).ready(function () {
    $("#task2-form").submit(function (e) {
        $("#btn-task2-submit")
        .removeClass("bg-submitButton hover:bg-darkerSubmitButton hover:text-gray-300")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});

$(document).ready(function () {
    $("#task3-form").submit(function (e) {
        $("#btn-task3-submit")
        .removeClass("bg-submitButton hover:bg-darkerSubmitButton hover:text-gray-300")
        .addClass("cursor-wait bg-multiStepBoxColor")
        .attr("disabled", true);
        return true;
    });
});
