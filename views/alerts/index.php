<?php

use app\assets\AppAlertsAsset;

$this->title = 'Apicação Colaborativa para Ciclistas';
?>

<?php
AppAlertsAsset::register($this);
?>

<div id='map' class='right-buffer-minus3 left-buffer-minus3'>
    
</div>

<div class='left-buffer-minus3'>
    <div class='row top-buffer-2'>
            <div class="col-lg-12 col-xs-12">
                <div class="col-lg-4 col-xs-4">
                    <h2>Alertas <span class='glyphicon glyphicon-alert'></span></h2>
                      <div>
                            <div>
                              <!-- Nav tabs -->
                              <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#active-alerts" aria-controls="active-alerts" role="tab" data-toggle="tab">Ativos</a></li>
                                <li role="presentation"><a href="#absent-warning" aria-controls="absent-warning" role="tab" data-toggle="tab">Avisos de Inexistência</a></li>
                              </ul>

                              <!-- Tab panes -->
                              <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade in active" id="active-alerts">...</div>
                                <div role="tabpanel" class="tab-pane fade" id="absent-warning">...</div>
                              </div>
                            </div>
                      </div>
                </div>
            </div>
    </div>
</div>

<?php // echo $this->render('_modals.php'); ?>