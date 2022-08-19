$(function(){
    if($(".label-notifications").text() == 0){
        $(".label-notifications").attr("hidden", true);
    }else{
        $(".label-notifications").removeAttr('hidden');
    }

    if($(".label-notifications-msg").text() == 0){
        $(".label-notifications-msg").attr("hidden", true);
    }else{
        $(".label-notifications-msg").removeAttr('hidden');
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

    $.ajax({
        url: './private-message/notification',
        type: 'GET',
        success: function(response){
            $(".label-notifications-msg").html(response);
            if(response == 0){
                $(".label-notifications-msg").attr("hidden", true);
            }else{
                $(".label-notifications-msg").removeAttr('hidden');
            }
        }
    });

    $.ajax({
        url: '../private-message/notification',
        type: 'GET',
        success: function(response){
            $(".label-notifications-msg").html(response);
            if(response == 0){
                $(".label-notifications-msg").attr("hidden", true);
            }else{
                $(".label-notifications-msg").removeAttr('hidden');
            }
        }
    });
}
