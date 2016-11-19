<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */
/* @var $route string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>

<?php $model = new \app\models\Users(); ?>

<div class="site-login col-lg-offset-1">

    <?php $form = ActiveForm::begin([
        'id' => 'signup-form',
        'options' => ['class' => 'form-horizontal'],
        'action' => Url::to(['site/signup']),
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            //'labelOptions' => ['class' => 'col-lg-1 control-label'],
            'labelOptions' => ['class' => 'col-lg-1 sr-only'],
        ],
    ]); ?>

        <?= $form->field($model, 'first_name')->textInput(['autofocus' => true, 'placeholder' => 'Primeiro nome']) ?>

        <?= $form->field($model, 'last_name')->passwordInput(['placeholder' => 'Último nome']) ?>
        
        <?= $form->field($model, 'how_to_be_called')->textInput(['placeholder' => 'Come quer ser chamado?']) ?>

        <?= $form->field($model, 'username')->passwordInput(['placeholder' => 'Nome de usuário']) ?>
        
        <?= $form->field($model, 'email')->textInput(['placeholder' => 'E-mail']) ?>
    
        <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Senha']) ?>
    
        <?= $form->field($model, 'password_repeat')->passwordInput(['placeholder' => 'confirmação']) ?>

        <?= $form->field($model, 'avatar_file')->fileInput(['placeholder' => 'Imagem do perfil']) ?>

        <div class="form-group">
            <div class="col-lg-11">
                <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

    <?php /* <div class="col-lg-offset-1" style="color:#999;">
        Você pode entrar com <strong>admin/admin</strong> ou <strong>demo/demo</strong>.<br>
        To modify the username/password, please check out the code <code>app\models\User::$users</code>.
    </div>*/ ?>
</div>
