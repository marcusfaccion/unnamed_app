$('body').on('click', '#home_btn_my_location', function(){
    showMyLocation();
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