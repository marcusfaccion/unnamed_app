<?php
use yii\widgets\Pjax;
use app\assets\AppAccountAsset;
?>

<?php 
AppAccountAsset::register($this); 
?>

<?php $this->title = Yii::$app->name.' - Conta de usuário';?>

<?php Pjax::begin([
        'id' => 'pjax-account-form',
        'enablePushState'=>false,
        'clientOptions'=> [
                    //Função executada no evento pjax:success
                    'account_success'=> in_array(Yii::$app->controller->action->id,['begin'])?new yii\web\JsExpression('
                                function(data, status, xhr){
                                     ;;
                                   );
                                }
                            '):'function(){;;}',
                ],
        //'formSelector'=>'#bike-keepers-widget-form',
]);?>

<?=Yii::$app->controller->renderPartial('_form', ['user'=>$user]);?>

<?php Pjax::end(); ?>