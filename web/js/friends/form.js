//var alert_type_desc;

$('body').on('click', 'button.friends.cancel', function(e){
    $('#home_actions_modal').modal('hide'); 
});
$('body').on('click', 'button.friends.save', function(e){
    Loading.show();
    
    $('#friends-message-panel').fadeOut('now', function(){$(this).html('')});
    
    $.ajax({
         url: "friends/add",
         type: "POST",
         data: {UserFriendshipRequest : {requested_user_id: $(this).next().html()}},
         success: function (data) {
                $('#friends-message-panel').fadeIn('now', function(){$(this).html(data)});
         }
    });
    $(this).parent().parent().fadeOut('now', function(){$(this).remove()});
    $('#ac-user-friend').val('');
    Loading.hide();
});
// Search by button click 
$('body').on('click', '#friends-search-btn', function(e){
    $('#ac-user-friend').autocomplete("close");
    Loading.show();
    $('#friends-message-panel').fadeOut('now', function(){$(this).html('')});
    $.ajax({
         url: "friends/get-friends",
         type: "POST",
         data: {Users : {full_name: $('#ac-user-friend').val()}},
         success: function (data) {
            $('#friends-subscribe-panel').html(data);
         }
    });
    Loading.hide();
});
// Search by Enter press
function onFriendSearchKeyPress(e){
    $('#ac-user-friend').autocomplete("close");
    if(e.which == 13) {
        Loading.show();
        $('#friends-message-panel').fadeOut('now', function(){$(this).html('')});
        $.ajax({
             url: "friends/get-friends",
             type: "POST",
             data: {Users : {full_name: $('#ac-user-friend').val()}},
             success: function (data) {
                $('#friends-subscribe-panel').html(data);
             }
        });
        Loading.hide();
    }
};

//$('body').on('focus', 'button.friends.save', function(e){   
//    // Gerando GeoJSON para salvar geometria
//    $('#friends-widget-form').find("input[id='bikekeepers-geojson-string']").val(JSON.stringify(L.marker(selectedlatlng,{}).toGeoJSON().geometry));
//    //alert_type_desc = $('#friends-widget-form').find("input[id='AlertTypes_description']").val();
//    isAjax = $('#friends-widget-form').find('.isAjax').val();
//    
//});
//                            