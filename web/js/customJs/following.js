$(function(){
    
    //Config basica 
    //container son los items a paginar dentro de donde
    //item son los items a paginar
    //controles de paginacion
    //next hace una peticion ajax a donde apunte next link
    //mostrar 5 elementos por "pagina"
    var ias = jQuery.ias({
        container: '.box-users',
        item: '.user-item',
        pagination: '.pagination',
        next: '.pagination .next_link',
        triggerPageThreshold: 5
    });

    //agrego boton de ver mas después de haber hecho 3 scrolls osea 3 cargas
    ias.extension(new IASTriggerExtension({
        text: 'Show more',
        offset: 3,
    }));

    //agrego efecto de loader al cargar
    ias.extension(new IASSpinnerExtension({
        src: '../../img/ajax-loader.gif'
    }));

    //Para cuando no hay mas registros
    ias.extension(new IASNoneLeftExtension({
        text: 'There are no more people'
    }));

    //Son dos eventos necesarios para que no se rompa con el infinite scroll
    ias.on('ready', function(event){
        followButtons();
    });

    ias.on('rendered', function(event){
        followButtons();
    });
});

function followButtons(){
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