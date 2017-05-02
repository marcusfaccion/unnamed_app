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
                <div class="col-lg-offset-1 col-xs-offset-1">
                    <h2>Alertas <span class='glyphicon glyphicon-alert'></span></h2>
                      <div>
                            <div>
                              <!-- Nav tabs -->
                              <ul class="nav nav-tabs" role="tablist">
                                  <li role="presentation" class="active">
                                      <a href="#active-alerts" aria-controls="active-alerts" role="tab" data-toggle="tab">Ativos <span class="badge"><?=count($user->activeAlerts)?></span></a>
                                  </li>
                                <li role="presentation">
                                    <a href="#nonexistent-warnings" aria-controls="nonexistent-warnings" role="tab" data-toggle="tab">Avisos de Inexistência <span class="badge"><?=count($user->nonexistentAlerts)?></span></a>
                                </li>
                              </ul>

                              <!-- Tab panes -->
                              <div class="tab-content">
                                  <div role="tabpanel" class="tab-pane fade in active" id="active-alerts">
                                      <?=Yii::$app->controller->renderPartial('_active-alerts',['alerts'=>$user->activeAlerts])?>
                                  </div>
                                  <div role="tabpanel" class="tab-pane fade" id="nonexistent-warnings">
                                      <p>teste</p><p>teste</p>
                                      <?php // Yii::$app->controller->renderPartial('_nonexistent-warnings',['alerts'=>$user->nonexistentAlerts])?>
                                  </div>
                              </div>
                            </div>
                      </div>
                </div>
            </div>
    </div>
</div>

<?php echo $this->render('_modals.php'); ?>