<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $user app\models\LoginForm */
/* @var $route string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\assets\SiteSignupAsset;
use kartik\file\FileInput;
use app\models\Users;
?>

<?php
SiteSignupAsset::register($this);
?>

<?php $user = isset($user)?$user:new Users(['scenario'=>Users::SCENARIO_CREATE]);?>

<div class="site-login col-lg-offset-1">
     <?php $flash_success = Yii::$app->session->getFlash('signup-success'); ?>
    <?php if($flash_success): ?>
    <div id='site-signup-notice' class='alert alert-success alert-dismissible' role='alert'>
        <button type="button" class="close" data-dismiss="alert" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
        <?=$flash_success?>
    </div>
    <?php endif; ?>
    
    <?php $form = ActiveForm::begin([
        'id' => 'site-signup-form',
        'options' => ['class' => 'form-horizontal', 'enctype'=>'multipart/form-data'],
        'action' => Url::to(['site/signup']),
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            //'labelOptions' => ['class' => 'col-lg-1 control-label'],
            'labelOptions' => ['class' => 'col-lg-1 sr-only'],
        ],
    ]); ?>

        <?= $form->field($user, 'first_name')->textInput(['autofocus' => $user->hasErrors()?true:false, 'placeholder' => 'Primeiro nome']) ?>

        <?= $form->field($user, 'last_name')->textInput(['placeholder' => 'Último nome']) ?>
        
        <?= $form->field($user, 'how_to_be_called')->textInput(['placeholder' => 'Come quer ser chamado?']) ?>

        <?= $form->field($user, 'username')->textInput(['placeholder' => 'Nome de usuário']) ?>
        
        <?= $form->field($user, 'email')->textInput(['placeholder' => 'E-mail']) ?>
        
        <?= $form->field($user, 'question')->textInput(['placeholder' => $user->getAttributeLabel('question')]) ?>
        
        <?= $form->field($user, 'answer')->textInput(['placeholder' => $user->getAttributeLabel('answer')]) ?>
    
        <?= $form->field($user, 'password')->passwordInput(['placeholder' => 'Senha']) ?>
    
        <?= $form->field($user, 'password_repeat')->passwordInput(['placeholder' => 'confirmação']) ?>

        <?php // $form->field($user, 'avatar_file')->fileInput(['placeholder'=>$user->getAttributeLabel('avatar_file'), 'class'=>'file-loading']) ?>
        
        <?= $form->field($user, 'avatar_file')->widget(FileInput::classname(),  ['options' => ['accept' => 'image/*'], 'pluginOptions'=>['initialCaption'=>$user->getAttributeLabel('avatar_file'),'showUpload'=>false, 'language'=>Yii::$app->language, 'allowedFileTypes'=>['image'], 'browseLabel'=>'Avatar', 'resizeImage'=>true]]) ?>

        <div class="form-group">
            <div class="col-lg-11">
                <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary site-signup', 'id' => 'site-signup-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

    <?php /* <div class="col-lg-offset-1" style="color:#999;">
        Você pode entrar com <strong>admin/admin</strong> ou <strong>demo/demo</strong>.<br>
        To modify the username/password, please check out the code <code>app\models\User::$users</code>.
    </div>*/ ?>
</div>
