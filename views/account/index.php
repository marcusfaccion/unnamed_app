<?php

use yii\bootstrap\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\models\BikeKeepers;
//use yii\helpers\Url;
use marcusfaccion\helpers\String;

?>

<?php $this->title = 'Bike Social - Conta de usuário';?>

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

<div class="row bottom-buffer-2">
    <div class="col-lg-6 col-md-6 col-xs-7">
        <h3 class="text-muted text-right strong-8">Dados do Usuário</h3>  
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-3 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12">
                <?=Html::img($user->avatar, ['class'=>'img-circle tall-px5-25 wide-px5-25'])?>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 top-buffer-1">
                <cite class="strong-5">"<?=$user->pharse?>"</cite>
            </div>
       </div>
    </div>
    <div class="col-lg-6 col-md-9 col-xs-12 top-buffer-4">
        
    
<?php $form = ActiveForm::begin([
             //'action'=>  Url::to(['/bike-keeper/item/create']),
                'id' =>'account-form',
                'options' => ['data' => ['pjax' => true], 'enctype' => 'multipart/form-data', 'id'=>'account-form'],
                'method' => 'post',
                'action' => Url::to('users/update'),
                'fieldConfig' => [
                    'template' => "<div class='row top-buffer-1'><div class=\"col-lg-10 col-xs-8\">{label}</div>\n<div class=\"col-lg-10 col-xs-11\">{input}</div>\n<div class=\"col-lg-2 col-xs-8\">{error}</div></div>",
                    'labelOptions' => ['class' => ''],
                ],
        ]); 
?>

<?=Yii::$app->controller->renderPartial('@app/views/users/_form', ['user'=>$user, 'form'=>$form])?>

<?php $form->end(); ?>
        </div>  
</div>

<?php Pjax::end(); ?>