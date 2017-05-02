<?php 
use app\assets\AppAlertsAsset;
use app\models\AlertTypes;
use marcusfaccion\helpers\String;
?>
    <div class="alerts-widget-index">
        <div id='alerts-widget-menu' class="row col-xs-offset-1">
        <?php foreach (AlertTypes::find()->orderBy('id desc')->all() as $alert_type):?>
          <div class="col-xs-6 col-md-4">
                <div class="row">
                    <div class="col-xs-12">
                        <a class="btn alert-trigger" id="aalert_<?=$alert_type->id?>" ><img class='img-responsive' src="images/icons/<?php echo String::changeChars($alert_type->iconfileName(), String::PTBR_DIACR_SEARCH, String::PTBR_DIACR_REPLACE);?>"></a>
                    </div>
                    <div class="col-xs-12">
                        <label class="col-md-offset-1"><?php echo $alert_type->description; ?></label>
                    </div>
                </div> 
           </div>
            <?php endforeach; ?>
       </div>
       <div id="home_actions_alerts_form" class="row col-xs-offset-1 bottom-buffer-5">
           
       </div>
    </div>
 
