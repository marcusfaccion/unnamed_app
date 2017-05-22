<?php
/**
 *  @var $alert Alert model
 *  @var $alert_type AlertTypes model
 */
use yii\bootstrap\Html;
use yii\widgets\ActiveForm;
//use yii\helpers\Url;
use marcusfaccion\helpers\String;
?>

<?php if(in_array(Yii::$app->controller->id, ['alerts','bikeKeepers'])):?>
<div class="left-buffer-3">
<?php endif;?>


<div class="row">
    <div class="col-lg-12">
        <h2>Perigo na Via <span class="glyphicon glyphicon-bullhorn"></span></h1>
        <small>* Campos obrigatórios </small>
    </div>
</div>

<?php $form = ActiveForm::begin([
         //'action'=>  Url::to(['/alert/item/create']),
            'id' =>'alerts-widget-form',
            'fieldConfig' => [
                'template' => "<div class='row top-buffer-1'><div class=\"col-lg-10 col-xs-8\">{label}</div>\n<div class=\"col-lg-10 col-xs-11\">{input}</div>\n<div class=\"col-lg-2 col-xs-8\">{error}</div></div>",
                'labelOptions' => ['class' => ''],
            ],
    ]); ?>

<?php echo $form->field($alert, 'title', ['options' =>['class'=>'']])->textInput(['autofocus'=>true , 'id'=>$alert->formName().'_title']);?>

<?php echo $form->field($alert, 'description', ['options' => ['class'=>'']])->textArea(['id'=>$alert->formName().'_description', 'class' => 'wide-12 form-control']);?>

<?php // Model Alerts ?>
<?php if(!$alert->isNewRecord){ echo Html::hiddenInput($alert->formName().'[id]', $alert->id, ['id'=>$alert->formName().'_id']);}?>
<?php echo Html::hiddenInput($alert->formName().'[user_id]', $alert->user_id, ['id'=>$alert->formName().'_user_id']);?>
<?php echo Html::hiddenInput($alert->formName().'[type_id]', $alert->type_id, ['id'=>$alert->formName().'_type_id']);?>
<?=Html::hiddenInput($alert->formName().'[address]', $alert->address, ['id'=>  strtolower($alert->formName()).'-address']);?>
<?php echo Html::hiddenInput($alert->formName().'[geojson_string]', $alert->geojson_string, ['id'=>$alert->formName().'_geojson_string']);?>

<?php // Model AlertTypes ?>
<?php echo Html::hiddenInput($alert_type->formName().'[description]', strtolower(String::changeChars($alert_type->description, String::PTBR_DIACR_SEARCH, String::PTBR_DIACR_REPLACE)), ['id'=>$alert_type->formName().'_description']);?>

<?php // Model Users ?>
<?php echo Html::hiddenInput($alert_user->formName().'[username]', $alert_user->username, ['id'=>$alert_user->formName().'_username']);?>

<?php // Define se a requisição é via Ajax   ?>
<?php echo Html::hiddenInput('isAjax', true, ['class' => 'isAjax','id'=>'alerts-widget-form_isAjax']);?>

<div class="top-buffer-2 text-center">
    <?php echo Html::button('Voltar', ['class'=>'btn btn-danger alert-back']);?>
    <?php echo Html::button('Salvar', ['class'=>'btn btn-success alert-save']);?>
</div>
<?php $form->end();?>

<?php if(in_array(Yii::$app->controller->id, ['alerts','bikeKeepers'])):?>
</div>
<?php endif;?>
