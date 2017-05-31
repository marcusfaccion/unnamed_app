<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */
/* @var $route string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>

<?php 
$model = (!isset($model)) ? new \app\models\LoginForm():$model; 
?>
<?php $form_number = !isset($form_number)? 1 : $form_number ?>

<div class="site-login col-lg-offset-1">

    <?php $form = ActiveForm::begin([
        'id' => 'login-form'.$form_number,
        'options' => ['class' => 'form-horizontal'],
        'action' => Url::to(['site/login']),
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            //'labelOptions' => ['class' => 'col-lg-1 control-label'],
            'labelOptions' => ['class' => 'col-lg-1 sr-only'],
        ],
    ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => 'Usuário']) ?>

        <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Senha']) ?>

        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"col-lg-5\">{input} {label} <a class='strong left-buffer-3' href='#site-signup-form' onclick=$('#modal_login').modal('hide')>Criar conta?</a> <a class='strong left-buffer-3' data-toggle='modal' href='#site_password_reset_modal' onclick=$('#modal_login').modal('hide')>Esqueceu a senha?</a></div>\n<div class=\"col-lg-7\">{error}</div>",
        ]) ?>
        
        <div class="form-group">
            <div class="col-lg-11">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

    <?php /* <div class="col-lg-offset-1" style="color:#999;">
        Você pode entrar com <strong>admin/admin</strong> ou <strong>demo/demo</strong>.<br>
        To modify the username/password, please check out the code <code>app\models\User::$users</code>.
    </div>*/ ?>
</div>
