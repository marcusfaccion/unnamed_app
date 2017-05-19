<?php

use app\assets\AppAlertsAsset;

$this->title = 'Bike Social - Alertas';
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
                      <div id='alerts-container'>
                            <?=Yii::$app->controller->renderPartial('_alerts_manager',['user'=>$user])?>
                      </div>
                </div>
            </div>
    </div>
</div>

<?php echo $this->render('_modals.php'); ?>