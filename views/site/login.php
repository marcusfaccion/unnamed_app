<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Entrar';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <?=$this->renderFile('@app/views/site/_form_login.php', ['form_number' => 2,'model'=>$model])?>
</div>

<?=Yii::$app->controller->renderPartial('_modals');?>
