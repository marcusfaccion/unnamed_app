<?php
use yii\bootstrap\Html;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
/* @var $this yii\web\View */
$this->title = 'Apicação Colaborativa para Ciclistas';
?>

<h1>Index Feed!<?=Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s')?></h1>

 <?php
                      echo AutoComplete::widget([
                            'id' => 'ac-user-friend',
                            'name' => 'ac-user-friend',
                            'clientOptions' => [
                                'source' => new JsExpression('
                                            function (request, response) {
                                                $.ajax({
                                                     url: "users/friends-search",
                                                     type: "GET",
                                                     data: request,
                                                     success: function (data) {
                                                         response($.map(JSON.parse(data), function (desc, val) {
                                                             return {
                                                                 label: desc.label,
                                                                 value: val.value
                                                             };
                                                          }));}
                                                });
                                            }
                                        '),
                                    'open' => new JsExpression('
                                                function( event, ui ) {
                                                    console.log(ui);
                                                }
                                            '),
                                'minLength'=> 2,
                                'delay'=> 100
                            ],
                        ]);
                  ?>


<?php $alert = new app\models\Alerts();?>
<div class="col-lg-offset-2 field-<?=strtolower($alert->formName())?>_duration_date required">
    <label class="col-lg-12" for="<?=strtolower($alert->formName())?>_duration_date"><?=$alert->getAttributeLabel('duration_date')?></label>
    <div class="col-lg-7">
        <?php 
                          dosamigos\datetimepicker\DateTimePicker::begin([
            'model' => $alert,
            'id'=>$alert->formName()."_duration_date",
            'attribute' => 'duration_date',
            'language' => 'pt-BR',
            'size' => 'ms',
            'pickButtonIcon' => 'glyphicon glyphicon-calendar',
            'clientOptions' => [
                'autoclose' => true,
                'todayHighlight'=>true,
                'startDate'=>yii::$app->formatter->asDatetime('now'),
                'format' => 'dd-mm-yyyy hh:ii:ss',
                'todayBtn' => true,
            ],
            'clientEvents'=>[
                'show'=>'function(){alert(\'Mostrar\')}',
                'changeDate'=>'function(){alert(\'Mostrar2\')}',
                //'hide'=>'console.log("ok2")',
            ]
        ]);?>
        <?php dosamigos\datetimepicker\DateTimePicker::end();?>
    </div>
    <div class="col-lg-5"><div class="help-block"></div></div>
</div>