$(document).ready(function(){

    $(".nick-input").blur(function(){
        var nick = this.value;
        console.log(nick);
        $.ajax({
            url: './nick-test',
            data: {nick: nick},
            type: 'POST',
            success: function(response){
                console.log(response);
                if(response === "used"){
                    $(".nick-input").css("border-bottom", "1px solid red");
                }else{
                    $(".nick-input").css("border-bottom", "1px solid green");
                }
            }
        });
    });

});