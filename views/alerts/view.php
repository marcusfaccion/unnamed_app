<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
/* @var yii\web\View $this */
/* @var app\modules\alerts\models\Alerts $alert  */
/* @var string $flash_success contém mensagem de sucesso ou null*/
$this->title = $alert->title;
?>
<div id='alerts-widget-viewer' class="alerts-view">
    
 <div class="row">
     <div class="col-lg-10 col-xs-11">
    <h3 class="text-success"><strong>Alerta criado <span class="glyphicon glyphicon-ok-sign text-success"></span></strong></h3>
     </div>
  </div> 

    <div class="row top-buffer-2">
        <div class="col-lg-10 col-xs-11">
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Fechar"><span aria-hidden="true">×</span></button>
                    <strong><?=Yii::$app->session->hasFlash('successfully-saved-alerts')?Yii::$app->session->getFlash('successfully-saved-alerts'):'Este alerta está publicado!'; ?></strong>
            </div>
        </div>    
    </div> 
    
    <div class="row top-buffer-2">
        <div class="col-lg-10 col-xs-11">
    <?= DetailView::widget([
        'model' => $alert,
        'attributes' => [
            [
                'attribute' => 'title',
                //'label' => '',
                'visible'=>!empty($alert->title),
            ],
            'description:ntext',
            [
                'attribute'=>'type_id',
                'value'=>$alert->type->description,
            ],
            [
                'attribute'=>'user_id',
                'value'=>$alert->user->how_to_be_called,
            ],
            [
                'attribute'=>'created_date',
                //'value'=>$alert->created_date,
                'format'=>'relativeTime'
            ],
            [
                'attribute'=>'likes',
                'visible'=>!empty($alert->likes),
            ],
            [
                'attribute'=>'dislikes',
                'visible'=>!empty($alert->dislikes),
            ],
        ],
        'options'=> ['class'=>'table table-condensed table-hover'] 
    ]) ?>
    </div>
   </div>     
</div>
