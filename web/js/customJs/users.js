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
        src: '../img/ajax-loader.gif'
    }));

    //Para cuando no hay mas registros
    ias.extension(new IASNoneLeftExtension({
        text: 'There are no more people'
    }));

    //Son dos eventos necesarios para que no se rompa con el infinite scroll
    ias.on('ready', function(event){
        followButtonsUsers();
    });

    ias.on('rendered', function(event){
        followButtonsUsers();
    });
});
