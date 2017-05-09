<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
/* @var yii\web\View $this */
/* @var app\modules\bike_keepers\models\Alerts $bike_keeper  */
/* @var string $flash_success contém mensagem de sucesso ou null*/
$this->title = $bike_keeper->title;
?>
<div id='bike_keepers-widget-viewer' class="bike_keepers-view">
    
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $flash_success = Yii::$app->session->getFlash('successfully-saved-bike_keeper'); ?>
    <?php if($flash_success): ?>
    <div id='bike_keepers-widget-notice' class='alert alert-success alert-dismissible' role='alert'>
        <button type="button" class="close" data-dismiss="alert" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
        <?=$flash_success?>
    </div>
    <?php endif; ?>
    
    <p>
        <?= Html::a('Update', ['update', 'id' => $bike_keeper->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $bike_keeper->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $bike_keeper,
        'attributes' => [
            'id',
            'title',
            'description:ntext',
            'user_id',
            'created_date',
            'public',
            'likes',
            'dislikes',
            'updated_date',
            'cost',
        ],
    ]) ?>
    <?php // Variável para controle de exibição da mensagem de salvamento dos dados ?>
    <?php $form = ActiveForm::begin(); ?> 
    <?=Html::hiddenInput($bike_keeper->formName()."[saved]", true, ['class'=>'saved' ,'id'=>$bike_keeper->formName().'_saved'])?>
    <?=Html::hiddenInput($bike_keeper->formName()."[id]", $bike_keeper->id, ['id'=>$bike_keeper->formName().'_id'])?>
    <?=Html::hiddenInput($bike_keeper->formName()."[geojson_string]", $bike_keeper->toFeature(), ['id'=>$bike_keeper->formName().'_geojson_string'])?>
    <?php $form->end(); ?>
</div>