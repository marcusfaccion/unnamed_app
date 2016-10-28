<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $alert app\modules\alerts\models\Alerts */

$this->title = $alert->title;
?>
<div class="alerts-view">
    
    <h1><?= Html::encode($this->title) ?></h1>
    <div id='alerts-widget-notice' class='alert alert-success alert-dismissible' role='alert'>
        <button type="button" class="close" data-dismiss="alert" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
        <?=Yii::$app->session->getFlash('successfully-saved-alerts')?>
    </div>
    
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
    <?=Html::hiddenInput($alert->formName()."[saved]", true, ['class'=>'saved' ,'id'=>$alert->formName().'_saved'])?>
</div>
