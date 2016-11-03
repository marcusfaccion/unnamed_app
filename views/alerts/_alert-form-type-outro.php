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

<h1 class='col-lg-offset-2'>Outros alertas</h1>

<?php $form = ActiveForm::begin([
         //'action'=>  Url::to(['/alert/item/create']),
            'id' =>'alerts-widget-form',
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-7\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-12'],
            ],
    ]); ?>

<?php echo $form->field($alert, 'title', ['options' =>['class'=>'col-lg-offset-2']])->textInput(['autofocus'=>true , 'id'=>$alert->formName().'_title']);?>

<?php echo $form->field($alert, 'description', ['options' => ['class'=>'col-lg-offset-2']])->textArea(['id'=>$alert->formName().'_description', 'class' => 'wide-12 form-control']);?>

<?php // Model Alerts ?>
<?php echo Html::hiddenInput($alert->formName().'[user_id]', $alert->user_id, ['id'=>$alert->formName().'_user_id']);?>
<?php echo Html::hiddenInput($alert->formName().'[type_id]', $alert->type_id, ['id'=>$alert->formName().'_type_id']);?>
<?php echo Html::hiddenInput($alert->formName().'[geojson_string]', $alert->geojson_string, ['id'=>$alert->formName().'_geojson_string']);?>

<?php // Model AlertTypes ?>
<?php echo Html::hiddenInput($alert_type->formName().'[description]', strtolower(String::changeChars($alert_type->description, String::PTBR_DIACR_SEARCH, String::PTBR_DIACR_REPLACE)), ['id'=>$alert_type->formName().'_description']);?>

<?php // Model Users ?>
<?php echo Html::hiddenInput($alert_user->formName().'[username]', $alert_user->username, ['id'=>$alert_user->formName().'_username']);?>

<?php // Define se a requisição é via Ajax   ?>
<?php echo Html::hiddenInput('isAjax', true, ['class' => 'isAjax','id'=>'alerts-widget-form_isAjax']);?>

<div class="top-buffer-4 col-lg-offset-4 col-lg-12">
    <?php echo Html::button('Voltar', ['class'=>'btn btn-danger back']);?>
    <?php echo Html::button('Salvar', ['class'=>'btn btn-success save']);?>
</div>
<?php $form->end();?>