$('body').on('click', '#active-alerts .alert-select-all', function(){
    $('#alerts-table').find('input[type=checkbox]').prop('checked',true);
});

$('body').on('click', '#active-alerts .alert-noselect-all', function(){
    $('#alerts-table').find('input[type=checkbox]').prop('checked',false);
});

$('body').on('click', '#alerts-table tr.data input', function(){
    $('#alerts-table tr.data').removeClass('success');
    $(this).parent().parent().toggleClass('success');
});

$('body').on('click', '#alerts-table .btn.alert-update', function(){
    app.alert.id = $(this).parent().parent().find('th input').val();
    $('#alert_update_modal').modal('show');
});

$('body').on('click', '#alerts-table .btn.alert-disable', function(){
    
});

$('body').on('click', '#alerts-table .btn.alert-view-on-map', function(){
    
});

$('body').on('click', '#alert_update_modal .btn.alert-save', function(){
    
    isAjax = $('#alerts-widget-form').find('.isAjax').val();
    
    if(isAjax){
        preloader.hide('alerts_alert_form', 'cicle_ball', '64', function(){
             $.ajax({
                type: 'POST',
                url: 'alerts/update',
                async: false,
                data:  $('#alerts-widget-form').serialize(),
                success: function(response){
                     $('#alerts_alert_form').html('');
                     preloader.show('alerts_alert_form', 'cicle_ball', '64', function (){
                        $('#alerts_alert_form').html(response).hide().fadeIn('fast');  
                     });
                }
             });
        });
    }
});

$('body').on('click', '#alert_update_modal .btn.alert-back', function(){
    $('#alert_update_modal').modal('hide');
});