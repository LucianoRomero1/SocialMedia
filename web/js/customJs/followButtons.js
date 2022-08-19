
function followButtonsUsers(){
    //unbind es para evitar q se haga 250 veces
    $(".btn-follow").unbind("click").click(function(){
        $(this).attr("hidden", true);
        //Lo busco con parent porque está dentro del mismo div
        $(this).parent().find(".btn-unfollow").removeAttr('hidden');
        $.ajax({
            url: './follow',
            type: 'POST',
            data: {followed: $(this).attr("data-followed")},
            success: function(response){
                console.log(response);
            }
        });
    });

    $(".btn-unfollow").unbind("click").click(function(){
        $(this).attr("hidden", true);
        //Lo busco con parent porque está dentro del mismo div
        $(this).parent().find(".btn-follow").removeAttr('hidden');
        $.ajax({
            url: './unfollow',
            type: 'POST',
            data: {followed: $(this).attr("data-followed")},
            success: function(response){
                console.log(response);
            }
        });
    });
    
}


function followButtonsProfile(){
    //unbind es para evitar q se haga 250 veces
    $(".btn-follow").unbind("click").click(function(){
        $(this).attr("hidden", true);
        //Lo busco con parent porque está dentro del mismo div
        $(this).parent().find(".btn-unfollow").removeAttr('hidden');
        $.ajax({
            url: '../follow',
            type: 'POST',
            data: {followed: $(this).attr("data-followed")},
            success: function(response){
                console.log(response);
            }
        });
    });

    $(".btn-unfollow").unbind("click").click(function(){
        $(this).attr("hidden", true);
        //Lo busco con parent porque está dentro del mismo div
        $(this).parent().find(".btn-follow").removeAttr('hidden');
        $.ajax({
            url: '../unfollow',
            type: 'POST',
            data: {followed: $(this).attr("data-followed")},
            success: function(response){
                console.log(response);
            }
        });
    });
    
}