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
