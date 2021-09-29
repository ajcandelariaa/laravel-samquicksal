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
