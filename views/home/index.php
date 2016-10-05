<?php
use yii\bootstrap\Html;
/* @var $this yii\web\View */
$this->title = 'Apicação Colaborativa para Ciclistas';
?>
<div id='map'>
    <div class="app-horizontal-widget">   
        <div class="gb_hb gb_wf gb_R gb_vf gb_fa">
            <div class="gb_hc gb_wf gb_R">
                <div class="gb_ga" guidedhelpid="gbifp" id="gbsfw">
                    <a role="button" onclick="$('#home_actions_trigger').val($(this).find('span').text()+';friends')" class="btn btn-default"><span>Amigos</span></a>
                    <a role="button" onclick="map.locate({setView: map_cfg.locate.setView, enableHighAccuracy: map_cfg.locate.enableHighAccuracy , watch: map_cfg.locate.watch});" class="btn btn-default"><span>Minha localização</span></a>
                    <a role="button" onclick="$('#home_actions_trigger').val($(this).find('span').text()+';layers')" class="btn btn-default"><span>Filtros</span></a>
                    <?php echo Html::hiddenInput("home_actions_trigger", 'Alertas;alerts', ['id'=>'home_actions_trigger']);?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->render('_modals.php'); ?>
<script>
var _return;
var mapbox_url_style = 'mapbox://styles/marcusfaccion/cita05dqg000d2iry8gtjoyi9';
var mapbox_accessToken = 'pk.eyJ1IjoibWFyY3VzZmFjY2lvbiIsImEiOiJjaXNxZ29jcHMwMjRyMnNwaHVxcmRlYjg4In0.eX6DVM4oZvmsJJn8o3B_oA';
var map;
var map_popup_menu = L.popup();
var map_cfg = {
               latlng: [-22.909, -43.688],
               zoom:    11.0,
               url_style: mapbox_url_style,
               accessToken: mapbox_accessToken,
               locate: {
                   setView: false,
                   watch:   false,
                   maxZoom: 9999999,
                   timeout: 10*1000, //10 secs
                   maximumAge:  0,
                   enableHighAccuracy: true,
               },
               popup_menu: {
                   getContent: function(){
                        $.ajax({
                            type: 'GET',
                            url: '?r=home/build-popup',
                            async: false,
                            success: function(response){
                                _return = response; 
                            }
                       });
                       return _return;
                   },
               },
};

$(document).ready(function() {
        //Bootstrapping
        if (!"geolocation" in navigator) {
            alert('Seu navergador não possui suporte a geolocalização ou está desativada');
        }
        L.mapbox.accessToken = map_cfg.accessToken;
        map = new L.mapbox.map('map');
        var style_layer = L.mapbox.styleLayer(map_cfg.url_style).addTo(map);
        map.setView(map_cfg.latlng, map_cfg.zoom);
        map_popup_menu.setContent(map_cfg.popup_menu.getContent());
        
        //Listeners
        map.on('locationfound', onLocationFound);
        map.on('locationerror', onLocationError);
        map.on('contextmenu', onContextMenuFired);
});

// Triggered Functions
function onLocationFound(e) {
    var radius = (e.accuracy / 2).toFixed(1);

    L.marker(e.latlng).addTo(map)
        .bindPopup("Você está " + radius + " metros deste ponto").openPopup();

    L.circle(e.latlng, radius).addTo(map);
}
function onLocationError(e) {
    alert(e.message);
}
function onContextMenuFired(e){
    map_popup_menu.setLatLng(e.latlng);
    map.openPopup(map_popup_menu);
}
</script>