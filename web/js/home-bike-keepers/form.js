$('body').on('change', '.bike-keepers.input_show_trigger', function(e){
    if(($(this).attr('value'))==0){
        $('.bike-keepers-input-hidden'+$(this).attr('data-target-input')).fadeIn('now').removeClass('hide');
        $('.bike-keepers-input-hidden'+$(this).attr('data-target-input')+' input').attr('disabled', false);
    }else{
        var radio = $(this);
        $('.bike-keepers-input-hidden'+$(this).attr('data-target-input')).fadeOut('now', function(){
            $('.bike-keepers-input-hidden'+radio.attr('data-target-input')).addClass('hide');
            $('.bike-keepers-input-hidden'+radio.attr('data-target-input')+' input').attr('disabled', true);
        });
    }
});

$('body').on('focus', 'button.bike-keepers.save', function(e){   
    // Gerando GeoJSON para salvar geometria
    $('#bike-keepers-widget-form').find("input[id='bikekeepers-geojson-string']").val(JSON.stringify(L.marker(selectedlatlng,{}).toGeoJSON().geometry));
    //alert_type_desc = $('#bike-keepers-widget-form').find("input[id='AlertTypes_description']").val();
    isAjax = $('#bike-keepers-widget-form').find('.isAjax').val();
    
});