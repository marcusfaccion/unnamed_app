$('body').on('click', '#home_btn_my_location', function(){
    if(showMyLocation()){
        //Atualizando o menu do mapa quando a navegação for ativada
        map_popup_menu.setContent(map_conf.popup_menu.getContent({nav:1}));
        map_popup_menu.update();
    }else{
        //Atualizando o menu do mapa quando a navegação for desativada
        map_popup_menu.setContent(map_conf.popup_menu.getContent({nav:0}));
        map_popup_menu.update();
    }
    
});
$('body').on('click', '#user-navigation-pane-toggle, #home-user-menu-navigation', function(){
    if($('#user-navigation-pane-toggle').children('span').hasClass('glyphicon-chevron-right')){
        $("#user-navigation-container").animate( {'margin-left' : "0px"}, 400);
        $('#user-navigation-pane-toggle').children('span').removeClass('glyphicon-chevron-right');
        $('#user-navigation-pane-toggle').children('span').addClass('glyphicon-chevron-left');
    }else{
        $("#user-navigation-container").animate( {'margin-left' : "-1000px"}, 400);
        $('#user-navigation-pane-toggle').children('span').removeClass('glyphicon-chevron-left');
        $('#user-navigation-pane-toggle').children('span').addClass('glyphicon-chevron-right');
    }
});

// Clique dos botões do Modal de Confirmação
$('body').on('click', '#no-confirm, #yes-confirm', function(){
    app.user_confirmation = parseInt($(this).val());
    
    // Executa ou não a requisição após a confirmação
    if(app.user_confirmation){
        $.ajax(app.request.ajax);
    }
});