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
        'clientOptions'=> [
                    //Função executada no evento pjax:success
                    'bike_keeper_success'=> new yii\web\JsExpression('
                                function(data, status, xhr){
                                     geoJSON_layer.bike_keepers.addData(JSON.parse($(\'#bike-keepers-widget-viewer\').find("input[id=\'BikeKeepers_geojson_string\']").val()),
                                    {    
                                        pointToLayer: generateBikeKeeperMarkerFeature,
                                    //  onEachFeature: onEachBikeKeeperMarkerFeature,
                                    }
                                   );
                                }
                            '),
                ],
        //'formSelector'=>'#bike-keepers-widget-form',
]);?>

<?php if(in_array(Yii::$app->controller->id, ['bike-keepers'])):?>
<div class="left-buffer-3">
<?php endif;?>
    
    <div class="row">
    <div class="col-lg-12">
        <h2>Bicicletário <span class="glyphicon glyphicon-lock"></span></h1>
        <small>* Campos obrigatórios </small>
    </div>
    </div>

    <?php $form = ActiveForm::begin([
             //'action'=>  Url::to(['/bike-keeper/item/create']),
                'id' =>'bike-keepers-widget-form',
                'options' => ['data' => ['pjax' => true], 'enctype' => 'multipart/form-data', 'id'=>'bike-keepers-widget-form'],
                'method' => 'post',
                'action' => Yii::$app->controller->action->id==='update'?Url::to('bike-keepers/update'):Url::to('bike-keepers/create'),
                'fieldConfig' => [
                    'template' => "<div class='row top-buffer-1'><div class=\"col-lg-10 col-xs-8\">{label}</div>\n<div class=\"col-lg-10 col-xs-11\">{input}</div>\n<div class=\"col-lg-2 col-xs-8\">{error}</div></div>",
                    'labelOptions' => ['class' => ''],
                ],
        ]); 
    ?>

        <?php echo $form->field($bike_keeper, 'title', ['options' =>['class'=>'']])->textInput(['autofocus'=>true]);?>
        <?php echo $form->field($bike_keeper, 'description',['options' => ['class'=>''],'template' => "<div class='row top-buffer-1'><div class='col-lg-10 col-xs-8 top-buffer-2'>{label}<span class='glyphicon glyphicon-info-sign'></span></div>\n<div class='col-lg-10 col-xs-11'>{input}</div>\n<div class='col-lg-2 col-xs-8'>{error}</div></div>", 'labelOptions'=>['class'=>'hasTooltip', 'data-toggle'=>'tooltip', 'data-placement'=>'right', 'title'=>'Informações sobre o bicicletário, dicas de localização, ponto de referência, horário de funcionamento...']])->textArea(['class' => 'wide-12 form-control']);?>
        <?php echo $form->field($bike_keeper, 'business_hours',['options' => ['class'=>''],'template' => "<div class='row top-buffer-1'><div class='col-lg-10 col-xs-8 top-buffer-2'>{label}<span class='glyphicon glyphicon-info-sign'></span></div>\n<div class='col-lg-10 col-xs-11'>{input}</div>\n<div class='col-lg-2 col-xs-8'>{error}</div></div>", 'labelOptions'=>['class'=>'hasTooltip', 'data-toggle'=>'tooltip', 'data-placement'=>'right', 'title'=>'Informe os dias e horário nos quais o bicicletário pode ser utilizado ']])->textArea(['class' => 'wide-12 form-control', 'onfocus'=>'$(this).parent().prev().find(\'label\').tooltip(\'show\')', 'onblur'=>'$(this).parent().prev().find(\'label\').tooltip(\'hide\')']);?>
        <?php echo $form->field($bike_keeper, 'capacity', ['options' =>['class'=>'']])->input('number',['min'=>1]);?>
        <?php echo $form->field($bike_keeper, 'outdoor', ['template' => "<div class='row top-buffer-1'><div class='col-lg-10 col-xs-8 top-buffer-2'>{label}<span class='glyphicon glyphicon-info-sign'></span></div>\n<div class='col-lg-10 col-xs-11 parent-requires'>{input}</div>\n<div class='col-lg-2 col-xs-8'>{error}</div></div>", 'labelOptions'=>['class'=>'hasTooltip', 'data-toggle'=>'tooltip', 'data-placement'=>'right', 'title'=>'O bicicletário é localizado ao ar livre?'],  'options'=>['class'=>'']])->radioList([1=>'Sim',0=>'Não']); ?>
        <?php echo $form->field($bike_keeper, 'public', ['options'=>['class'=>''], 'template' => "<div class='row top-buffer-1'><div class='col-lg-10 col-xs-8 top-buffer-2'>{label}<span class='glyphicon glyphicon-info-sign'></span></div>\n<div class='col-lg-10 col-xs-11 parent-requires'>{input}</div>\n<div class='col-lg-2 col-xs-8'>{error}</div></div>", 'labelOptions'=>['class'=>'hasTooltip', 'data-toggle'=>'tooltip', 'data-placement'=>'right', 'title'=>'É cobrada alguma taxa?']])->radioList([1=>'Sim',0=>'Não'], ['itemOptions'=>['class'=>'bike-keepers input_show_trigger', 'data-target-input'=>1]]); ?>
        <?php if(is_numeric($bike_keeper->cost) && !$bike_keeper->public): ?>
            <?php echo $form->field($bike_keeper, 'cost', ['options'=>['class'=>''], 'template' => "<div value=1 class='row top-buffer-1 bike-keepers-input-hidden1'><div class='col-lg-10 col-xs-8 top-buffer-3 bottom-buffer-2'>{label}<span class='glyphicon glyphicon-info-sign'></span></div>\n<div class='col-lg-10 col-xs-11'><div class='input-group'><div class='input-group-addon'>R$</div>{input}</div></div>\n<div class='col-lg-10 col-xs-8'>{error}</div></div>", 'labelOptions'=>['class'=>'hasTooltip', 'data-toggle'=>'tooltip', 'data-placement'=>'right', 'title'=>'Qual é a diária cobrada para estacionar?']])->input('number', ['min'=>0, 'step'=>0.05, 'value'=>(float)$bike_keeper->cost]); ?>
        <?php else: ?>
            <?php echo $form->field($bike_keeper, 'cost', ['options'=>['class'=>''], 'template' => "<div value=1 class='row top-buffer-1 hide bike-keepers-input-hidden1'><div class='col-lg-10 col-xs-8 top-buffer-3 bottom-buffer-2'>{label}<span class='glyphicon glyphicon-info-sign'></span></div>\n<div class='col-lg-10 col-xs-11'><div class='input-group'><div class='input-group-addon'>R$</div>{input}</div></div>\n<div class='col-lg-10 col-xs-8'>{error}</div></div>", 'labelOptions'=>['class'=>'hasTooltip', 'data-toggle'=>'tooltip', 'data-placement'=>'right', 'title'=>'Qual é a diária cobrada para estacionar?']])->input('number', ['min'=>0, 'step'=>0.05, 'value'=>0,'disabled'=>true]); ?>
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

        <?php if(Yii::$app->controller->action->id==='update'):?>
            <?php echo $form->field($bike_keeper, 'email', ['template' => "<div class='row top-buffer-1 bike-keepers-input-hidden1 ".(!$bike_keeper->public?'':'hide')."'><div class='col-lg-10 col-xs-11 top-buffer-2'>{label}<div class='input-group'><span class='input-group-addon'>@</span><div>{input}</div></div>\n<div class='col-lg-2 col-xs-8'>{error}</div></div></div>", 'labelOptions'=>['class'=>'hasTooltip', 'data-toggle'=>'tooltip', 'data-placement'=>'right', 'title'=>'Email de contato do bicicletário'],  'options'=>['class'=>'']])->input('email',['disabled'=>(bool)$bike_keeper->public]); ?>
            <?php echo $form->field($bike_keeper, 'tel', ['template' => "<div class='row top-buffer-1 bike-keepers-input-hidden1 ".(!$bike_keeper->public?'':'hide')."'><div class='col-lg-10 col-xs-11 top-buffer-2'>{label}<div class='input-group'><span class='input-group-addon'><span class='glyphicon glyphicon-phone-alt'></span></span><div>{input}</div></div>\n<div class='col-lg-2 col-xs-8'>{error}</div></div></div>", 'labelOptions'=>['class'=>'hasTooltip', 'data-toggle'=>'tooltip', 'data-placement'=>'right', 'title'=>'Telefone de contato do bicicletário'],  'options'=>['class'=>'']])->input('text',['disabled'=>(bool)$bike_keeper->public]); ?>
        <?php else:?>
            <?php echo $form->field($bike_keeper, 'email', ['template' => "<div class='row top-buffer-1 bike-keepers-input-hidden1 hide'><div class='col-lg-10 col-xs-11 top-buffer-2'>{label}<div class='input-group'><span class='input-group-addon'>@</span><div>{input}</div></div>\n<div class='col-lg-2 col-xs-8'>{error}</div></div></div>", 'labelOptions'=>['class'=>'hasTooltip', 'data-toggle'=>'tooltip', 'data-placement'=>'right', 'title'=>'Email de contato do bicicletário'],  'options'=>['class'=>'']])->input('email',['disabled'=>true]); ?>
            <?php echo $form->field($bike_keeper, 'tel', ['template' => "<div class='row top-buffer-1 bike-keepers-input-hidden1 hide'><div class='col-lg-10 col-xs-11 top-buffer-2'>{label}<div class='input-group'><span class='input-group-addon'><span class='glyphicon glyphicon-phone-alt'></span></span><div>{input}</div></div>\n<div class='col-lg-2 col-xs-8'>{error}</div></div></div>", 'labelOptions'=>['class'=>'hasTooltip', 'data-toggle'=>'tooltip', 'data-placement'=>'right', 'title'=>'Telefone de contato do bicicletário'],  'options'=>['class'=>'']])->input('text',['disabled'=>true]); ?>
        <?php endif;?>

        
       <?php echo $form->field($bike_keeper, 'multimedia_files[]', ['template' => "<div class='row top-buffer-1'><div class='col-lg-10 col-xs-8 top-buffer-2'>{label}<span class='glyphicon glyphicon-info-sign'></span></div>\n<div class='col-lg-10 col-xs-11 parent-requires'>{input}</div>\n<div class='col-lg-2 col-xs-8'>{error}</div></div>", 'labelOptions'=>['class'=>'hasTooltip', 'data-toggle'=>'tooltip', 'data-placement'=>'right', 'title'=>'Inclua fotos ou vídeos que identifiquem o local'],  'options'=>['class'=>'', 'multiple'=>true]])->widget(FileInput::classname(),  ['options' => ['accept' => 'image/*,video/*', 'multiple'=>true], 'pluginOptions'=>['showUpload'=>false, 'fileActionSettings'=>['showZoom'=>false],'browseOnZoneClick'=>true, 'language'=>Yii::$app->language, 'allowedFileTypes'=>['image', 'video'], 'maxFileCount'=>5,'browseLabel'=>'Multimidia']]) ?>

        <?php // Model BikeKeepers ?>
        <?php if(Yii::$app->controller->action->id==='update'):?>
            <?=Html::hiddenInput($bike_keeper->formName().'[id]', $bike_keeper->id);?>
        <?php endif;?>
        <?=Html::hiddenInput($bike_keeper->formName().'[user_id]', $bike_keeper->user_id);?>
        <?=Html::hiddenInput($bike_keeper->formName().'[address]', $bike_keeper->address, ['id'=>  strtolower($bike_keeper->formName()).'-address']);?>
        <?=Html::hiddenInput($bike_keeper->formName().'[geojson_string]', $bike_keeper->geojson_string, ['id'=>  strtolower($bike_keeper->formName()).'-geojson-string']);?>

        <?php // Define se a requisição é via Ajax   ?>
        <?php echo Html::hiddenInput('isAjax', true, ['class' => 'isAjax','id'=>'bike-keepers-widget-form_isAjax']);?>
        <div class="top-buffer-4 text-center">
            <?php if(Yii::$app->controller->action->id==='update'):?>
                <?php echo Html::button('Cancelar', ['class'=>'btn btn-danger bike-keeper-update cancel', 'data-dismiss'=>'modal']);?>
                <?php echo Html::button('Salvar', ['class'=>'btn btn-success  bike-keeper-update save' , 'type'=>'submit']);?>
            <?php else:?>
                <?php echo Html::button('Cancelar', ['class'=>'btn btn-danger bike-keepers cancel', 'data-dismiss'=>'modal']);?>
                <?php echo Html::button('Salvar', ['class'=>'btn btn-success bike-keepers save' , 'type'=>'submit']);?>
            <?php endif;?>
        </div>
    <?php $form->end(); ?>
<?php if(in_array(Yii::$app->controller->id, ['bike-keepers'])):?>
    </div>
<?php endif;?>

<?php Pjax::end(); ?>