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

$('body').on('click', 'button.alerts.back', function(e){
    preloader.hide('home_actions_alerts_form', 'cicle_ball', '64', function(){
        $('#home_actions_alerts_form').html('');
        preloader.show('alerts-widget-menu', 'cicle_ball', '64');  
    }); 
});

$('body').on('click', 'button.alerts.save', function(e){
   
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
});