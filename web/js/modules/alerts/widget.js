$('body').on('click', 'a.alert-trigger', function(e){
    $('#home_actions_alerts_form').html(img_preload);
    $.ajax({
            type: 'GET',
            url: '?r=alerts/widget/form/&type_id='+e.currentTarget['id'].split('_')[1],    
            success: function(response){
                $('#home_actions_alerts_form').html(response);
            }
    });
});