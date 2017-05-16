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
                    <h3><strong>Alertas</strong> <span class='glyphicon glyphicon-bullhorn'></span></h3>
                      <div>
                            <div>
                              <!-- Nav tabs -->
                              <ul  id='alerts-nav-tabs' class="nav nav-tabs" role="tablist">
                                  <li role="presentation" class="active" id='alerts-nav-tab-active'>
                                      <a href="#active-alerts" aria-controls="active-alerts" role="tab" data-toggle="tab">Ativos <span class="badge"><?=count($user->activeAlerts)?></span></a>
                                  </li>
                                <li role="presentation" alerts-nav-tab-problem>
                                    <a href="#active-nonalerts" aria-controls="active-nonalerts" role="tab" data-toggle="tab">Problemas <span class="badge"><?=count($user->activeNonexistentAlerts)?></span></a>
                                </li>
                              </ul>

                              <!-- Tab panes -->
                              <div class="tab-content">
                                  <div role="tabpanel" class="tab-pane fade in active" id="active-alerts">
                                      <?=Yii::$app->controller->renderPartial('_active_alerts',['alerts'=>$user->activeAlerts])?>
                                  </div>
                                  <div role="tabpanel" class="tab-pane fade" id="active-nonalerts">
                                      <?=Yii::$app->controller->renderPartial('_active_nonalerts',['non_alerts'=>$user->activeNonexistentAlerts])?>
                                  </div>
                              </div>
                            </div>
                      </div>
                </div>
            </div>
    </div>
</div>

<?php echo $this->render('_modals.php'); ?>