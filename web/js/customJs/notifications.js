$(function(){
    if($(".label-notifications").text() == 0){
        $(".label-notifications").attr("hidden", true);
    }else{
        $(".label-notifications").removeAttr('hidden');
    }

    getNotifications();
    setInterval(function(){
        getNotifications();
    }, 60000);
});

function getNotifications(){
    $.ajax({
        url: './notifications/get',
        type: 'GET',
        success: function(response){
            $(".label-notifications").html(response);
            if(response == 0){
                $(".label-notifications").attr("hidden", true);
            }else{
                $(".label-notifications").removeAttr('hidden');
            }
        }
    });

    $.ajax({
        url: '../notifications/get',
        type: 'GET',
        success: function(response){
            $(".label-notifications").html(response);
            if(response == 0){
                $(".label-notifications").attr("hidden", true);
            }else{
                $(".label-notifications").removeAttr('hidden');
            }
        }
    });
}
