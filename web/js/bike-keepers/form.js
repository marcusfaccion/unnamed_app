var alert_type_desc;

$('body').on('click', 'button.bike-keepers.cancel', function(e){
    $('#home_actions_modal').modal('hide'); 
});

$('body').on('change', '.bikekeeprs.input_show_trigger', function(e){
    if(($(this).attr('value'))==1){
        $('.bikekeeprs-input-hidden'+$(this).attr('data-target-input')).fadeIn('now').removeClass('hide');
        $('.bikekeeprs-input-hidden'+$(this).attr('data-target-input')+' input').attr('disabled', false);
    }else{
        var radio = $(this);
        $('.bikekeeprs-input-hidden'+$(this).attr('data-target-input')).fadeOut('now', function(){
            $('.bikekeeprs-input-hidden'+radio.attr('data-target-input')).addClass('hide');
            $('.bikekeeprs-input-hidden'+radio.attr('data-target-input')+' input').attr('disabled', true);
        });
    }
});

$('body').on('click', 'button.bike-keepers.save', function(e){
   
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