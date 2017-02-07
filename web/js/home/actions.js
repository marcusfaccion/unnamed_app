$('body').on('click', '#home_btn_my_location', function(){
    showMyLocation();
});
$('body').on('click', '#geocoding-pane-toggle', function(){
    if($(this).children('span').hasClass('glyphicon-chevron-right')){
        $(this).fadeOut('now');
        $("#geocoding-pane").animate( {'margin-left' : "0px"}, 1000);
        $(this).animate({'left': parseInt($("#geocoding-pane").width()+5)+'px'}, 1000);
        $(this).show('now');
        $(this).children('span').removeClass('glyphicon-chevron-right');
        $(this).children('span').addClass('glyphicon-chevron-left');
    }else{
        $("#geocoding-pane").animate( {'margin-left' : "-1000px"}, 800);
        $(this).animate({'left': '0px'});
        $(this).children('span').removeClass('glyphicon-chevron-left');
        $(this).children('span').addClass('glyphicon-chevron-right');
    }
});