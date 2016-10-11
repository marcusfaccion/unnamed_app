<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>

<?php $model = new \app\models\LoginForm(); ?>
<?php $numero_formulario = !isset($numero_formulario)? 1 : $numero_formulario ?>

<div class="site-login">
    <p>Por favor preencha os seguintes campos de login:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form'.$numero_formulario,
        'options' => ['class' => 'form-horizontal'],
        'action' => Url::to(['site/login']),
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
        ]) ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

    <?php /* <div class="col-lg-offset-1" style="color:#999;">
        Você pode entrar com <strong>admin/admin</strong> ou <strong>demo/demo</strong>.<br>
        To modify the username/password, please check out the code <code>app\models\User::$users</code>.
    </div>*/ ?>
</div>
