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

$('body').on('blur', '.bike-keepers-reverse-geocoding-trigger', function(e){
    var address = null;
    var str_address = '';                                            
    console.log(11111)
    //Reverse Geocode assíncrono para o endereço aproximado, uma vez que o OSM não mapeia os números das casas.
    app.geocoder.reverse(app.latLng,1,function(e){
        address = e[0];
        if(typeof(address.properties)!='undefined'){
            str_address += ((address.properties.address.road!='undefined')?address.properties.address.road:'')+', s/n, '; 
            str_address += ((address.properties.address.suburb!='undefined')?address.properties.address.suburb:'')+', '; 
            str_address += ((address.properties.address.city!='undefined')?address.properties.address.city:'')+'/'; 
            str_address += ((address.properties.address.state!='undefined')?address.properties.address.state:'')+' - '; 
            str_address += ((address.properties.address.country!='undefined')?address.properties.address.country:''); 
        }
        //Endereço obtido do geocoding reverso
        $('#pjax-bike-keepers-widget-form').find("input[id='bikekeepers-address']").val(str_address);
    });
});

$('body').on('focus', 'button.bike-keepers.save', function(e){   
    // Gerando GeoJSON para salvar geometria
    $('#bike-keepers-widget-form').find("input[id='bikekeepers-geojson-string']").val(JSON.stringify(L.marker(selectedlatlng,{}).toGeoJSON().geometry));
    //alert_type_desc = $('#bike-keepers-widget-form').find("input[id='AlertTypes_description']").val();
    isAjax = $('#bike-keepers-widget-form').find('.isAjax').val();
    
});