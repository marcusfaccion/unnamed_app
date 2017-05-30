<?php
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php Pjax::begin([
        'id' => 'pjax-site-user-challange-form',
        'enablePushState'=>false,
        'clientOptions'=> [
                    //Função executada no evento pjax:success
                    'user_challange_success'=> in_array(Yii::$app->controller->action->id,['begin'])?new yii\web\JsExpression('
                                function(data, status, xhr){
                                    ;;
                                   );
                                }
                            '):'function(){;;}',
                ],
        //'formSelector'=>'#bike-keepers-widget-form',
]);?>
    
    <div class="row">
    <div class="col-lg-12">
        <small>* Campos obrigatórios </small>
    </div>
    </div>

    <?php $form = ActiveForm::begin([
             //'action'=>  Url::to(['/bike-keeper/item/create']),
                'id' =>'site-user-challange-form',
                'options' => ['data' => ['pjax' => true], 'enctype' => 'multipart/form-data', 'id'=>'site-user-challange-form'],
                'method' => 'post',
                'action' => Url::to('users/challange'),
                'fieldConfig' => [
                    'template' => "<div class='row top-buffer-1'><div class=\"col-lg-10 col-xs-8\">{label}</div>\n<div class=\"col-lg-10 col-xs-11\">{input}</div>\n<div class=\"col-lg-4 col-xs-8\">{error}</div></div>",
                    'labelOptions' => ['class' => ''],
                ],
        ]); 
    ?>
        <?php if($step==1):?>
            <h4 class="text-muted strong-6">Passo <?=$step?> de <?=$steps?>:</h4>
            <?=$form->field($user, 'email', ['template' => "<div class='row top-buffer-1'><div class='col-lg-8 col-xs-12 top-buffer-2'>{label}<div class='input-group'><span class='input-group-addon'>@</span><div>{input}</div></div>\n<div class='col-lg-4 col-xs-12'>{error}</div></div></div>", 'labelOptions'=>['class'=>'hasTooltip', 'data-toggle'=>'tooltip', 'data-placement'=>'right', 'title'=>'Email de cadastro'],  'options'=>['class'=>'']])->input('email',[]); ?>
            <?=Html::hiddenInput('step', 2, ['id'=>'challange-step']);?>
        <?php endif;?>
        
        <?php if($step==2):?>
            <h4 class="text-muted strong-6">Passo <?=$step?> de <?=$steps?>: Responda...</h4>
            <div class="jumbotron"><cite class="tsize-5">"<?=$user->question?>"</cite></div>
            <?=$form->field($user, 'answer', ['template' => "<div class='row top-buffer-1'><div class='col-lg-10 col-xs-11 top-buffer-2'><div>{input}</div>\n<div class='col-lg-4 col-xs-8'>{error}</div></div></div>", 'labelOptions'=>['class'=>'hasTooltip', 'data-toggle'=>'tooltip', 'data-placement'=>'right', 'title'=>'Resposta da pergunta'],  'options'=>['class'=>'']])->textInput(['placeholder' => 'Resposta...']); ?>
            <?=Html::hiddenInput('step', 3, ['id'=>'challange-step']);?>
            <?=Html::hiddenInput($user->formName()."[id]", $user->id, ['id'=>'challange-step']);?>
        <?php endif;?>
        
        <?php if($step==3):?>
            <h4 class="text-muted strong-6">Passo <?=$step?> de <?=$steps?>: Altere a senha</h4>
            <?php echo $form->field($user, 'password', ['template' => "<div class='row top-buffer-1'><div class='col-lg-10 col-xs-11 top-buffer-2'>{label}<div>{input}</div>\n<div class='col-lg-4 col-xs-8'>{error}</div></div></div>", 'labelOptions'=>['class'=>'hasTooltip', 'data-toggle'=>'tooltip', 'data-placement'=>'right', 'title'=>'Nova senha'],  'options'=>['class'=>'']])->input('password',[])?>
            <?php echo $form->field($user, 'password_repeat', ['template' => "<div class='row top-buffer-1'><div class='col-lg-10 col-xs-11 top-buffer-2'>{label}<div>{input}</div>\n<div class='col-lg-4 col-xs-8'>{error}</div></div></div>", 'labelOptions'=>['class'=>'hasTooltip', 'data-toggle'=>'tooltip', 'data-placement'=>'right', 'title'=>'Nova senha'],  'options'=>['class'=>'']])->input('password',[])?>
            <?=Html::hiddenInput('step', 4, ['id'=>'challange-step']);?>
            <?=Html::hiddenInput($user->formName()."[id]", $user->id, ['id'=>'challange-step']);?>
        <?php endif;?>

        <?php // Define se a requisição é via Ajax   ?>
        <?php echo Html::hiddenInput('isAjax', true, ['class' => 'isAjax','id'=>'site-user-challange-form_isAjax']);?>
        <div class="top-buffer-4 text-center">
                <?php echo Html::button('Cancelar', ['class'=>'btn btn-danger challange cancel', 'data-dismiss'=>'modal']);?>
                <?php echo Html::button('Enviar', ['class'=>'btn btn-success challange save' , 'type'=>'submit']);?>
        </div>
    <?php $form->end(); ?>

<?php Pjax::end(); ?>