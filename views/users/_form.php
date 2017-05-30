<?php
use yii\helpers\Html;
use kartik\file\FileInput;
?>
        <?=Html::hiddenInput($user->formName().'[id]', $user->id); ?>
        
        <?= $form->field($user, 'first_name')->textInput(['autofocus' => $user->hasErrors()?true:false, 'placeholder' => 'Primeiro nome']) ?>

        <?= $form->field($user, 'last_name')->textInput(['placeholder' => 'Último nome']) ?>
        
        <?= $form->field($user, 'how_to_be_called')->textInput(['placeholder' => 'Come quer ser chamado?']) ?>

        <?=(Yii::$app->controller->id==='site')?$form->field($user, 'username')->textInput(['placeholder' => 'Nome de usuário']):''; ?>
        
        <?= $form->field($user, 'email')->textInput(['placeholder' => 'E-mail']) ?>
        
        <?= $form->field($user, 'pharse')->textarea(['placeholder' => 'Uma frase com a qual se identifique']) ?>
        
        <?= $form->field($user, 'question')->textInput(['placeholder' => $user->getAttributeLabel('question')]) ?>
        
        <?= $form->field($user, 'answer')->textInput(['placeholder' => $user->getAttributeLabel('answer')]) ?>
    
        <?= $form->field($user, 'password')->passwordInput(['placeholder' => 'Senha']) ?>
    
        <?= $form->field($user, 'password_repeat')->passwordInput(['placeholder' => 'confirmação']) ?>

        <?php // $form->field($user, 'avatar_file')->fileInput(['placeholder'=>$user->getAttributeLabel('avatar_file'), 'class'=>'file-loading']) ?>
        
        <?= $form->field($user, 'avatar_file')->widget(FileInput::classname(),  ['options' => ['accept' => 'image/*'], 'pluginOptions'=>['initialCaption'=>$user->getAttributeLabel('avatar_file'),'showUpload'=>false, 'language'=>Yii::$app->language, 'allowedFileTypes'=>['image'], 'browseLabel'=>'Avatar', 'resizeImage'=>true]]) ?>

        <div class="form-group">
            <div class="bottom-buffer-2">
                <?= Html::submitButton('Enviar', ['class' => 'btn btn-success strong-7 site-signup', 'id' => 'site-signup-button']) ?>
            </div>
        </div>
