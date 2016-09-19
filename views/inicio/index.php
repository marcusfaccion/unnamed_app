<?php

/* @var $this yii\web\View */
$this->title = 'Apicação Colaborativa para Ciclistas';
?>
<div id='map'>
    <div class="app-horizontal-widget">   
        <div class="gb_hb gb_wf gb_R gb_vf gb_fa">
            <div class="gb_hc gb_wf gb_R">
                <div class="gb_ga" guidedhelpid="gbifp" id="gbsfw">
                    <span class="gb_7a gbii">Adicionar facilidade</span>
                    <span class="gb_7a gbii">Guardador de bike</span>
                    <span class="gb_7a gbii">Aluguel de bike</span>
                    <span class="gb_7a gbii">Social</span>
                    <span class="gb_7a gbii">Rotas</span>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
mapboxgl.accessToken = 'pk.eyJ1IjoibWFyY3VzZmFjY2lvbiIsImEiOiJjaXNxZ29jcHMwMjRyMnNwaHVxcmRlYjg4In0.eX6DVM4oZvmsJJn8o3B_oA';
var map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/marcusfaccion/cita05dqg000d2iry8gtjoyi9',
    //zoom: 13,
    //center: [-43.453460, -22.937948]
});
</script>