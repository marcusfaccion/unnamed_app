<?php
/**
 *  @var BikeKeepers $bike_keeper
 */

use yii\bootstrap\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\models\BikeKeepers;
//use yii\helpers\Url;
use marcusfaccion\helpers\String;
use kartik\file\FileInput;
?>
<?php $this->title = 'Cadastro - Bicicletário'; ?>

<?php Pjax::begin([
        'id' => 'pjax-bike-keepers-widget-form',
        'enablePushState'=>false,
        //'formSelector'=>'#bike-keepers-widget-form',
]);?>

    <h3 class='col-lg-offset-1'>Novo Bicicletário</h3>

    <?php $form = ActiveForm::begin([
             //'action'=>  Url::to(['/bike-keeper/item/create']),
                'id' =>'bike-keepers-widget-form',
                'options' => ['data' => ['pjax' => true], 'enctype' => 'multipart/form-data'],
                'method' => 'post',
                'action' => Url::to('bike-keepers/create'),
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-lg-8\">{input}</div>\n<div class=\"col-lg-12\">{error}</div>",
                    'labelOptions' => ['class' => 'col-lg-12'],
                ],
        ]); 
    ?>

        <?php echo $form->field($bike_keeper, 'title', ['options' =>['class'=>'col-lg-offset-1']])->textInput(['autofocus'=>true]);?>
        <?php echo $form->field($bike_keeper, 'description',['options' => ['class'=>'col-lg-offset-1'],'template' => "<div class='col-lg-12 top-buffer-3 bottom-buffer-2'>{label}<span class='glyphicon glyphicon-info-sign'></span></div>\n<div class='col-lg-8'>{input}</div>\n<div class='col-lg-12'>{error}</div>", 'labelOptions'=>['class'=>'hasTooltip', 'data-toggle'=>'tooltip', 'data-placement'=>'right', 'title'=>'Informações sobre o bicicletário, dicas de localização, ponto de referência, horário de funcionamento...']])->textArea(['class' => 'wide-12 form-control']);?>
        <?php echo $form->field($bike_keeper, 'capacity', ['options' =>['class'=>'col-lg-offset-1']])->input('number',['min'=>1]);?>
        <?php echo $form->field($bike_keeper, 'public', ['options'=>['class'=>'col-lg-offset-1'], 'template' => "<div class='col-lg-12 top-buffer-3 bottom-buffer-2'>{label}<span class='glyphicon glyphicon-info-sign'></span></div>\n<div class='col-lg-8'>{input}</div>\n<div class='col-lg-12'>{error}</div>", 'labelOptions'=>['class'=>'hasTooltip', 'data-toggle'=>'tooltip', 'data-placement'=>'right', 'title'=>'É cobrada alguma taxa?']])->radioList([1=>'Sim',0=>'Não'], ['itemOptions'=>['class'=>'bikekeeprs input_show_trigger', 'data-target-input'=>1]]); ?>
        <?php if(is_numeric($bike_keeper->cost)): ?>
            <?php echo $form->field($bike_keeper, 'cost', ['options'=>['class'=>'col-lg-offset-1'], 'template' => "<div value=1 class='bikekeeprs-input-hidden1'><div class='col-lg-12 top-buffer-3 bottom-buffer-2'>{label}<span class='glyphicon glyphicon-info-sign'></span></div>\n<div class='col-lg-8'><div class='input-group'><div class='input-group-addon'>R$</div>{input}</div></div>\n<div class='col-lg-12'>{error}</div></div>", 'labelOptions'=>['class'=>'hasTooltip', 'data-toggle'=>'tooltip', 'data-placement'=>'right', 'title'=>'Qual é o valor cobrado para estacionar?']])->input('number', ['min'=>1, 'step'=>0.05, 'value'=>(float)$bike_keeper->cost]); ?>
        <?php else: ?>
            <?php echo $form->field($bike_keeper, 'cost', ['options'=>['class'=>'col-lg-offset-1'], 'template' => "<div value=1 class='hide bikekeeprs-input-hidden1'><div class='col-lg-12 top-buffer-3 bottom-buffer-2'>{label}<span class='glyphicon glyphicon-info-sign'></span></div>\n<div class='col-lg-8'><div class='input-group'><div class='input-group-addon'>R$</div>{input}</div></div>\n<div class='col-lg-12'>{error}</div></div>", 'labelOptions'=>['class'=>'hasTooltip', 'data-toggle'=>'tooltip', 'data-placement'=>'right', 'title'=>'Qual é o valor cobrado para estacionar?']])->input('number', ['min'=>1, 'step'=>0.05, 'value'=>1,'disabled'=>true]); ?>
        <?php endif; ?>
        <?php /* <div class="col-lg-offset-1 field-bikekeepers-public required">
           <label class="col-lg-12"><span class="hasTooltip" data-toggle="tooltip" data-placement="r" title="É um bicicletário de uso público ou existem taixas de uso?">Público</span></label>
                <div class="col-lg-8"><input type="hidden" name="BikeKeepers[public]" value="">
                    <div id="bikekeepers-public">
                        <?php echo Html::activeRadioList($bike_keeper, 'public', [1=>'Sim   ',0=>'Não']); ?>
                    </div>
                </div>
                <div class="col-lg-4"><div class="help-block"></div></div>
        </div>*/?>
        <?php echo $form->field($bike_keeper, 'outdoor', ['template' => "<div class='col-lg-12 top-buffer-3 bottom-buffer-2'>{label}<span class='glyphicon glyphicon-info-sign'></span></div>\n<div class='col-lg-8'>{input}</div>\n<div class='col-lg-12'>{error}</div>", 'labelOptions'=>['class'=>'hasTooltip', 'data-toggle'=>'tooltip', 'data-placement'=>'right', 'title'=>'O bicicletário é localizado ao ar livre?'],  'options'=>['class'=>'col-lg-offset-1']])->radioList([1=>'Sim',0=>'Não']); ?>
        <?php echo $form->field($bike_keeper, 'multimedia_files[]', ['template' => "<div class='col-lg-12 top-buffer-3'>{label}<span class='glyphicon glyphicon-info-sign'></span></div>\n<div class='col-lg-8'>{input}</div>\n<div class='col-lg-12'>{error}</div>", 'labelOptions'=>['class'=>'hasTooltip', 'data-toggle'=>'tooltip', 'data-placement'=>'right', 'title'=>'Inclua fotos ou vídeos que identifiquem o local'],  'options'=>['class'=>'col-lg-offset-1', 'multiple'=>true]])->widget(FileInput::classname(),  ['options' => ['accept' => 'image/*,video/*', 'multiple'=>true], 'pluginOptions'=>['showUpload'=>false, 'fileActionSettings'=>['showZoom'=>false],'browseOnZoneClick'=>true, 'language'=>Yii::$app->language, 'allowedFileTypes'=>['image', 'video'], 'maxFileCount'=>5,'browseLabel'=>'Multimidia']]) ?>

        <?php // Model Alerts ?>
        <?php echo Html::hiddenInput($bike_keeper->formName().'[user_id]', $bike_keeper->user_id);?>
        <?php echo Html::hiddenInput($bike_keeper->formName().'[geojson_string]', $bike_keeper->geojson_string, ['id'=>  strtolower($bike_keeper->formName()).'-geojson-string']);?>

        <?php // Define se a requisição é via Ajax   ?>
        <?php echo Html::hiddenInput('isAjax', true, ['class' => 'isAjax','id'=>'bike-keepers-widget-form_isAjax']);?>
        <div class="top-buffer-4 col-lg-offset-2 col-lg-12">
            <?php echo Html::button('Cancelar', ['class'=>'btn btn-danger bike-keepers cancel']);?>
            <?php echo Html::button('Salvar', ['class'=>'btn btn-success bike-keepers save' , 'type'=>'submit']);?>
        </div>
    <?php $form->end(); ?>
<?php Pjax::end(); ?>