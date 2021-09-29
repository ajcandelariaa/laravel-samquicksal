$("#clickAboutRestaurant").click(function(){
  $("#aboutRestaurant").show();
  $("#foodMenu").hide();
  $("#taskRewards").hide();
  $("#promos").hide();
  $("#time").hide();
  $("#offense").hide();
});

$("#clickFoodMenu").click(function(){
    $("#aboutRestaurant").hide();
    $("#foodMenu").show();
    $("#taskRewards").hide();
    $("#promos").hide();
    $("#time").hide();
    $("#offense").hide();
});

$("#clickTaskRewards").click(function(){
    $("#aboutRestaurant").hide();
    $("#foodMenu").hide();
    $("#taskRewards").show();
    $("#promos").hide();
    $("#time").hide();
    $("#offense").hide();
});

$("#clickPromos").click(function(){
    $("#aboutRestaurant").hide();
    $("#foodMenu").hide();
    $("#taskRewards").hide();
    $("#promos").show();
    $("#time").hide();
    $("#offense").hide();
});

$("#clickManageTime").click(function(){
    $("#aboutRestaurant").hide();
    $("#foodMenu").hide();
    $("#taskRewards").hide();
    $("#promos").hide();
    $("#time").show();
    $("#offense").hide();
});

$("#clickManageOffense").click(function(){
    $("#aboutRestaurant").hide();
    $("#foodMenu").hide();
    $("#taskRewards").hide();
    $("#promos").hide();
    $("#time").hide();
    $("#offense").show();
});