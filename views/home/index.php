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
                    <a role="button" data-toggle="modal" data-target="#home_actions_modal" onclick="$('#home_actions_trigger').val($(this).find('span').text()+';alerts')" class="btn btn-default"><span>Alertas</span></a>
                    <?php // <a role="button" data-toggle="modal" data-target="#home_actions_modal" class="btn btn-default"><span class="a"> facilidade</span></a> ?>
                    <a role="button" data-toggle="modal" data-target="#home_actions_modal" onclick="$('#home_actions_trigger').val($(this).find('span').text()+';bike-keeper')" class="btn btn-default"><span>Guardador de bike</span></a>
                    <a role="button" data-toggle="modal" data-target="#home_actions_modal" onclick="$('#home_actions_trigger').val($(this).find('span').text()+';routes')" class="btn btn-default"><span>Rotas</span></a>
                    <a role="button" data-toggle="modal" data-target="#home_actions_modal" onclick="$('#home_actions_trigger').val($(this).find('span').text()+';events')" class="btn btn-default"><span>Eventos</span></a>
                    <a role="button" data-toggle="modal" data-target="#home_actions_modal" onclick="$('#home_actions_trigger').val($(this).find('span').text()+';renting')" class="btn btn-default"><span>Aluguel de bike</span></a>
                    <?php // <a role="button" data-toggle="modal" data-target="#home_actions_modal" onclick="$('#home_actions_trigger').val($(this).find('span').text()+';lending')" class="btn btn-default"><span>Social</span></a> ?>
                    <a role="button" onclick="map.locate({setView: map_cfg.locate.setView, watch: map_cfg.locate.watch});" class="btn btn-default"><span>Minha localização</span></a>
                    <?php echo Html::hiddenInput("home_actions_trigger", 'Alertas;alerts', ['id'=>'home_actions_trigger']);?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->render('_modals.php'); ?>
<script>
var mapbox_url_style = 'mapbox://styles/marcusfaccion/cita05dqg000d2iry8gtjoyi9';
var mapbox_accessToken = 'pk.eyJ1IjoibWFyY3VzZmFjY2lvbiIsImEiOiJjaXNxZ29jcHMwMjRyMnNwaHVxcmRlYjg4In0.eX6DVM4oZvmsJJn8o3B_oA';
var map_cfg = {
               latlong: [-22.850, -43.480],
               zoom:    11.0,
               url_style: mapbox_url_style,
               accessToken: mapbox_accessToken,
               locate: {
                   setView: false,
                   watch:   false,
                   maxZoom: 9999999,
                   timeout: 10*1000, //10 secs
                   maximumAge:  0,
                   enableHighAccuracy: false,
               },
};
var map;
$(document).ready(function() {
        if (!"geolocation" in navigator) {
            alert('Seu navergador não possui suporte a geolocalização ou está desativada');
        }
        L.mapbox.accessToken = map_cfg.accessToken;
        map = new L.mapbox.map('map');
        var style_layer = L.mapbox.styleLayer(map_cfg.url_style).addTo(map);
        map.setView(map_cfg.latlong, map_cfg.zoom);
        
        //Listeners
        map.on('locationfound', onLocationFound);
        map.on('locationerror', onLocationError);
        
});

function onLocationFound(e) {
    var radius = e.accuracy / 2;

    L.marker(e.latlng).addTo(map)
        .bindPopup("You are within " + radius + " meters from this point").openPopup();

    L.circle(e.latlng, radius).addTo(map);
}
function onLocationError(e) {
    alert(e.message);
}
</script>