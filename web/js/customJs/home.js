$(function(){

    var ias = jQuery.ias({
        container: '.timeline .box-content',
        item: '.publication-item',
        pagination: '.timeline .pagination',
        next: '.timeline .pagination .next_link',
        triggerPageThreshold: 5
    });

    ias.extension(new IASTriggerExtension({
        text: 'Show more',
        offset: 3,
    }));

    ias.extension(new IASSpinnerExtension({
        src: '../img/ajax-loader.gif'
    }));

    ias.extension(new IASNoneLeftExtension({
        text: 'There are no more post'
    }));

    ias.on('ready', function(event){
        buttonsHome();
    });

    ias.on('rendered', function(event){
        buttonsHome();
    });
});

function buttonsHome(){

    $('[data-toggle="tooltip"]').tooltip();

    //unbind para evitar tantos clicks
    $(".btn-img").unbind("click").click(function(){
        $(this).parent().find(".pub-image").fadeToggle();
    });

    $(".btn-delete-pub").unbind("click").click(function(){
        $(this).parent().parent().attr("hidden", true);
        $.ajax({
            url: './publication/delete/' + $(this).attr("data-id"),
            type: 'GET',
            success: function(response){
                console.log(response);
            }
        });
    });

    $(".btn-like").unbind("click").click(function(){
        $(this).attr("hidden", true);
        $(this).parent().find('.btn-unlike').removeAttr('hidden');
        $.ajax({
            url: './like/'+$(this).attr("data-id"),
            type: 'GET',
            success: function(response){
                console.log(response);
            }
        });
    });

    $(".btn-unlike").unbind("click").click(function(){
        $(this).attr("hidden", true);
        $(this).parent().find('.btn-like').removeAttr('hidden');
        $.ajax({
            url: './unlike/'+$(this).attr("data-id"),
            type: 'GET',
            success: function(response){
                console.log(response);
            }
        });
    });
}