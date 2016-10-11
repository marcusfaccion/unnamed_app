<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\AppAlertsAsset;
use yii\helpers\Url;
?>
<?php
AppAlertsAsset::register($this);
?>
    <?php
     $form = ActiveForm::begin([
            'action'=>  Url::to(['/alert/create']),
            'id' =>'alerts-widget-form',
            // more ActiveForm options
        ]);
    ?>
    <div class="alerts-widget-index">
        <div class="row col-xs-offset-2">
            <div class="col-xs-6 col-md-4">
                <div class="row">
                    <div class="col-xs-12">
                        <a class="btn alert-trigger" id="aalert_1" ><img src="images/icons/danger_cone_80.png"></a>
                    </div>
                    <div class="col-xs-12">
                        <label class="col-md-offset-1">Perigo na via</label>
                    </div>
                </div> 
           </div>
            <div class="col-xs-6 col-md-4">
                <div class="row">
                    <div class="col-xs-12">
                        <a class="btn alert-trigger" id="aalert_2"><img src="images/icons/alert_thief_80.png"></a>
                    </div>
                    <div class="col-xs-12">
                        <label class="col-md-offset-1">Furto e Roubo</label>
                    </div>
                </div> 
           </div>
            <div class="col-xs-6 col-md-4">
                <div class="row">
                    <div class="col-xs-12">
                        <a class="btn alert-trigger" id="aalert_3"><img src="images/icons/alert_construction_80.png"></a>
                    </div>
                    <div class="col-xs-12">
                        <label class="col-md-offset-1">Interdição</label> 
                    </div>
                </div> 
           </div>
             <?php /*
            <div class="col-xs-6 col-md-4">
                <div class="row">
                    <div class="col-xs-12">
                        <img src="images/icons/alert_construction_80.png" />
                    </div>
                    <div class="col-xs-12">
                        <input type="radio" name="inlineRadioOptions" id="alert_id_type" value="4"> Interdição
                    </div>
                </div> 
           </div>
            <div class="col-xs-6 col-md-4">
                <div class="row">
                    <div class="col-xs-12">
                        <img src="images/icons/default_80.png">
                    </div>
                    <div class="col-xs-12">
                        <input type="radio" name="inlineRadioOptions" id="alert_id_type" value="2"> Declive / Aclive
                    </div>
                </div> 
           </div>
            <div class="col-xs-6 col-md-4">
                <div class="row">
                    <div class="col-xs-12">
                        <img src="images/icons/default_80.png">
                    </div>
                    <div class="col-xs-12">
                        <input type="radio" name="inlineRadioOptions" id="alert_id_type" value="5"> Túnel
                    </div>
                </div> 
           </div>
             <div class="col-xs-6 col-md-4">
                <div class="row">
                    <div class="col-xs-12">
                        <img src="images/icons/default_80.png">
                    </div>
                    <div class="col-xs-12">
                        <input type="radio" name="inlineRadioOptions" id="alert_id_type" value="5"> Encosta
                    </div>
                </div> 
           </div>*/?>
       </div>
       <div id="home_actions_alerts_form" class="row col-xs-offset-1 top-buffer-5 bottom-buffer-5">
          
       </div>
    </div>
    <?php
        $form->end();
    ?>
