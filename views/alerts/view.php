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
    
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $flash_success = Yii::$app->session->getFlash('successfully-saved-alerts'); ?>
    <?php if($flash_success): ?>
    <div id='alerts-widget-notice' class='alert alert-success alert-dismissible' role='alert'>
        <button type="button" class="close" data-dismiss="alert" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
        <?=$flash_success?>
    </div>
    <?php endif; ?>
    
    <p>
        <?= Html::a('Update', ['update', 'id' => $alert->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $alert->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $alert,
        'attributes' => [
            'id',
            'title',
            'description:ntext',
            'type_id',
            'user_id',
            'created_date',
            'likes',
            'unlikes',
            'updated_date',
            'visible:boolean',
            'geom',
        ],
    ]) ?>
    <?php // Variável para controle de exibição da mensagem de salvamento dos dados ?>
    <?php $form = ActiveForm::begin(); ?> 
    <?=Html::hiddenInput($alert->formName()."[saved]", true, ['class'=>'saved' ,'id'=>$alert->formName().'_saved'])?>
    <?=Html::hiddenInput($alert->formName()."[id]", $alert->id, ['id'=>$alert->formName().'_id'])?>
    <?=Html::hiddenInput($alert->formName()."[geojson_string]", $alert->toFeature(), ['id'=>$alert->formName().'_geojson_string'])?>
    <?php $form->end(); ?>
</div>
