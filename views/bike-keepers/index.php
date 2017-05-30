<?php

use app\assets\AppBikeKeepersAsset;

$this->title = Yii::$app->name.' - Bicicletários';
?>

<?php
AppBikeKeepersAsset::register($this);
?>

<div id='map' class='right-buffer-minus3 left-buffer-minus3'>
    
</div>

<div class='left-buffer-minus3'>
    <div class='row top-buffer-2'>
            <div class="col-lg-12 col-xs-12">
                <div class="col-lg-offset-1 col-xs-offset-1">
                    <h3><strong>Bicicletários</strong> <span class='glyphicon glyphicon-home'></span></h3>
                      <div id='bike-keepers-container'>
                            <?=Yii::$app->controller->renderPartial('_bike_keepers_manager',['user'=>$user, 'config'=>$config])?>
                      </div>
                </div>
            </div>
    </div>
</div>

<?php echo $this->render('_modals.php'); ?>