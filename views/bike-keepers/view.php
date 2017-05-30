<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
/* @var yii\web\View $this */
/* @var app\modules\bike_keepers\models\BikeKeepers $bike_keeper  */
/* @var string $flash_success contém mensagem de sucesso ou null*/
$this->title = Yii::$app->name.' - Bicicletário';
?>
<div id='bike-keepers-widget-viewer' class="bike-keepers-view">
    
 <div class="row">
     <div class="col-lg-10 col-xs-11">
    <h3 class="text-success"><strong>Bicicletário <?=($bike_keeper->updated_date)?'atualizado':'criado'?> <span class="glyphicon glyphicon-ok-sign text-success"></span></strong></h3>
     </div>
  </div> 

    <div class="row top-buffer-2">
        <div class="col-lg-10 col-xs-11">
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Fechar"><span aria-hidden="true">×</span></button>
                    <strong><?=Yii::$app->session->hasFlash('successfully-saved-bike-keeper')?Yii::$app->session->getFlash('successfully-saved-bike-keeper'):'Este bicicletário já está publicado!'; ?></strong>
            </div>
        </div>    
    </div> 
    
    <div class="row top-buffer-2">
        <div class="col-lg-10 col-xs-11">
 <?= DetailView::widget([
        'model' => $bike_keeper,
        'attributes' => [
             [
                'attribute' => 'title',
                //'label' => '',
                'visible'=>!empty($bike_keeper->title),
            ],
            [
                'attribute'=>'description',
                'visible'=>!empty($bike_keeper->description),
                'value'=>$bike_keeper->description,
            ],
            [
                'attribute'=>'address',
                'value'=>$bike_keeper->address,
            ],
            [
                'attribute'=>'business_hours',
                'value'=>$bike_keeper->business_hours,
            ],
            [
                'attribute'=>'public',
                'label'=>'É público?',
                'visible'=>$bike_keeper->public!=null,
                'value'=>$bike_keeper->public?'Sim':'Não',
            ],
            [
                'attribute'=>'outdoor',
                'label'=>'É ao ar livre?',
                'value'=>$bike_keeper->outdoor?'Sim':'Não',
            ],
            [
                'attribute'=>'cost',
                'label'=>'Diária',
                'visible'=>$bike_keeper->cost>0,
                'format'=>'currency',
            ],
            [
                'attribute'=>'capacity',
                'value'=>$bike_keeper->capacity." ".Yii::t('app','{n, plural, =1{vaga} other{vagas}}',['n'=>$bike_keeper->capacity]),
            ],
            [
                'attribute'=>'user_id',
                'label'=>'Gerente',
                'value'=>$bike_keeper->user->how_to_be_called,
            ],
            [
                'attribute'=>'tel',
                'label'=>'Telefone',
                'value'=>$bike_keeper->tel,
                'visible'=>!empty($bike_keeper->tel),
            ],
            [
                'attribute'=>'email',
                'label'=>'Email',
                'value'=>$bike_keeper->email,
                'visible'=>!empty($bike_keeper->email),
            ],
            [
                'attribute'=>'created_date',
                'label'=>'Aberto desde',
                'format'=>'relativeTime'
            ],
            [
                'attribute'=>'likes',
                'label'=>'Número de aprovações',
                'format'=>'html',
                'value'=>"<span class='glyphicon glyphicon-thumbs-up text-success tsize-4'></span> <span class='badge tsize-3'>".$bike_keeper->likes."</span>",
                'visible'=>!empty($bike_keeper->likes),
            ],
            [
                'attribute'=>'dislikes',
                'label'=>'Número de desaprovações',
                'format'=>'html',
                'value'=>"<span class='glyphicon glyphicon-thumbs-down text-danger tsize-4'></span> <span class='badge tsize-3'>".$bike_keeper->dislikes."</span>",
                'visible'=>!empty($bike_keeper->dislikes),
            ],
            [
                'attribute'=>'updated_date',
                'label'=>'Última atualização',
                'contentOptions'=>['class'=>'text-danger'],
                'captionOptions'=>['class'=>'text-danger'],
                'visible'=>$bike_keeper->updated_date!=null,
                'format'=>'relativeTime'
            ],
        ],
     'options'=> ['class'=>'table table-condensed table-hover'] 
    ]) ?>
       </div>
   </div>     
    <?php // Variável para controle de exibição da mensagem de salvamento dos dados ?>
    <?php $form = ActiveForm::begin(); ?> 
    <?=Html::hiddenInput($bike_keeper->formName()."[saved]", true, ['class'=>'saved' ,'id'=>$bike_keeper->formName().'_saved'])?>
    <?=Html::hiddenInput($bike_keeper->formName()."[id]", $bike_keeper->id, ['id'=>$bike_keeper->formName().'_id'])?>
    <?=Html::hiddenInput($bike_keeper->formName()."[geojson_string]", $bike_keeper->toFeature(), ['id'=>$bike_keeper->formName().'_geojson_string'])?>
    <?php $form->end(); ?>
</div>