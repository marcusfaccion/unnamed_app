var alert_type_desc;

$('body').on('click', 'a.alert-trigger', function(e){
        /*if(!$('#home_actions_alerts_form').is(':hidden')){
            $('#home_actions_alerts_form').fadeOut();
        }*/
        preloader.hide('alerts-widget-menu', 'cicle_ball', '64',  function(){
            $.ajax({
                type: 'GET',
                async: false,
                url: 'alerts/form',
                data: { type_id: e.currentTarget['id'].split('_')[1]},
                success: function(response){
                    preloader.show('home_actions_alerts_form', 'cicle_ball', '64', function(){ 
                        $('#home_actions_alerts_form').html(response).hide().fadeIn('fast');
                    });
                }
            })
        });
});

$('body').on('click', 'button.alert-back', function(e){
    preloader.hide('home_actions_alerts_form', 'cicle_ball', '64', function(){
        $('#home_actions_alerts_form').html('');
        preloader.show('alerts-widget-menu', 'cicle_ball', '64');  
    }); 
});

$('body').on('click', 'button.alert-save', function(e){
    var address = null;
    var str_address = '';
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
        $('#alerts-widget-form').find("input[id='alerts-address']").val(str_address);
    
        // Gerando GeoJSON para salvar geometria
        $('#alerts-widget-form').find("input[id='Alerts_geojson_string']").val(JSON.stringify(L.marker(selectedlatlng,{}).toGeoJSON().geometry));
        alert_type_desc = $('#alerts-widget-form').find("input[id='AlertTypes_description']").val();
        isAjax = $('#alerts-widget-form').find('.isAjax').val();
    
        if(isAjax){
            preloader.hide('home_actions_alerts_form', 'cicle_ball', '64', function(){
                 $.ajax({
                    type: 'POST',
                    url: 'alerts/create',
                    async: false,
                    data:  $('#alerts-widget-form').serialize(),
                    success: function(response){
                         $('#home_actions_alerts_form').html('');
                         preloader.show('home_actions_alerts_form', 'cicle_ball', '64', function (){
                            $('#home_actions_alerts_form').html(response).hide().fadeIn('fast');  
                            if($('#alerts-widget-viewer').find('.saved').val()){
                                geoJSON_layer.alerts.addData(JSON.parse($('#alerts-widget-viewer').find("input[id='Alerts_geojson_string']").val()),
                                    {    
                                        pointToLayer: generateAlertMarkerFeature,
                                        onEachFeature: onEachAlertMarkerFeature,
                                    }
                                );
                                 /*var marker = L.marker(selectedlatlng,
                                {
                                    icon: Icons[alert_type_desc],
                                    title: $('#alerts-widget-form').find("input[id='Alerts_title']").val(),
                                    alt: alert_type_desc,
                                    riseOnHover: true
                                }).bindPopup(
                                    L.popup({
                                        className: 'popup-alert-'+alert_type_desc,
                                    })
                                    .setContent( popup.getContentAjax(
                                        '?r=alerts/item/render-popup',
                                        {
                                           type : 'post',
                                           async: false,
                                           data: $('#alerts-widget-form').serialize()
                                        }
                                    ))
                                )
                                .openPopup()
                                .addTo(map);*/
                           }

                         });
                    }
                 });
            });
        }
    },document);
    
    
});