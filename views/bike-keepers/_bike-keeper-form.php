<?php
/**
 *  @var BikeKeepers $bike_keeper
 */

use yii\bootstrap\Html;
use yii\widgets\ActiveForm;
//use yii\helpers\Url;
use marcusfaccion\helpers\String;
?>

<h1 class='col-lg-offset-2'>Novo guardador de bike</h1>

<?php $form = ActiveForm::begin([
         //'action'=>  Url::to(['/bike-keeper/item/create']),
            'id' =>'bike-keepers-widget-form',
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-7\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-12'],
            ],
    ]); ?>

<?php echo $form->field($bike_keeper, 'title', ['options' =>['class'=>'col-lg-offset-2']])->textInput(['autofocus'=>true , 'id'=>$bike_keeper->formName().'_title']);?>
<?php echo $form->field($bike_keeper, 'description', ['options' => ['class'=>'col-lg-offset-2']])->textArea(['id'=>$bike_keeper->formName().'_description', 'class' => 'wide-12 form-control']);?>
<?php echo $form->field($bike_keeper, 'capacity', ['options' =>['class'=>'col-lg-offset-2']])->textInput(['id'=>$bike_keeper->formName().'_capacity']);?>
<?php echo $form->field($bike_keeper, 'multimedia_files[]', ['options' =>['class'=>'col-lg-offset-2']])->fileInput(['id'=>$bike_keeper->formName().'_multimedia_files', 'multiple' => true, 'accept'=>['image/*', 'video/*']]);?>

<?php // Model Alerts ?>
<?php echo Html::hiddenInput($bike_keeper->formName().'[user_id]', $bike_keeper->user_id, ['id'=>$bike_keeper->formName().'_user_id']);?>
<?php echo Html::hiddenInput($bike_keeper->formName().'[geojson_string]', $bike_keeper->geojson_string, ['id'=>$bike_keeper->formName().'_geojson_string']);?>

<?php // Define se a requisição é via Ajax   ?>
<?php echo Html::hiddenInput('isAjax', true, ['class' => 'isAjax','id'=>'bike-keepers-widget-form_isAjax']);?>

<div class="top-buffer-4 col-lg-offset-4 col-lg-12">
    <?php echo Html::button('Cancelar', ['class'=>'btn btn-danger bike-keepers cancel']);?>
    <?php echo Html::button('Salvar', ['class'=>'btn btn-success bike-keepers save']);?>
</div>
<?php $form->end();?>