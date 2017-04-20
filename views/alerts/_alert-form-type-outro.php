<?php
/**
 *  @var $alert Alert model
 *  @var $alert_type AlertTypes model
 */
use yii\bootstrap\Html;
use yii\widgets\ActiveForm;
use dosamigos\datetimepicker\DateTimePicker;
//use yii\helpers\Url;
use marcusfaccion\helpers\String;
?>

<div class="row">
    <div class="col-lg-12">
        <h2 class=''>Outros alertas</h1>
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

<?php echo $form->field($alert, 'title', ['options' =>[]])->textInput(['autofocus'=>true , 'id'=>$alert->formName().'_title']);?>

<?=$form->field($alert, 'duration_date', ['options' =>['class'=>'']])->widget(DateTimePicker::className(), [
    'language' => 'pt-BR',
    'size' => 'ms',
    'pickButtonIcon' => 'glyphicon glyphicon-calendar',
    'clientOptions' => [
        'todayHighlight'=>true,
        'autoclose' => true,
        'todayBtn' => true,
        'startDate'=>yii::$app->formatter->asDatetime('now'),
        'format' => 'dd-mm-yyyy hh:ii:ss',
    ],
    'clientEvents'=>[
                'show'=>'function(){}',
                'hide'=>'function(e){
                            //Corrige bug do modal
                            e.currentTarget = div.input-group.date.input-ms;
                        }',
                'changeDate'=>'function(){}',
            ]
]);
?>

<?php echo $form->field($alert, 'description', ['options' => ['']])->textArea(['id'=>$alert->formName().'_description', 'class' => 'wide-12 form-control']);?>

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
    <?php echo Html::button('Voltar', ['class'=>'btn btn-danger alerts back']);?>
    <?php echo Html::button('Salvar', ['class'=>'btn btn-success alerts save']);?>
</div>
<?php $form->end();?>