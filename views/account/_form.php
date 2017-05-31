<?php
use yii\bootstrap\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>

<?php if(Yii::$app->session->hasFlash('successfully-saved')):?>
<div class="alert alert-dismissible alert-success" role='alert'>
    <p class='text-success'>
        <strong><?=Yii::$app->session->getFlash('successfully-saved')?></strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
    </p>
</div>
<?php endif;?>

<div class="row bottom-buffer-2">
    <div class="col-lg-6 col-md-6 col-xs-7">
        <h3 class="text-muted text-left strong-8"><?=$user->how_to_be_called?></h3>  
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-3 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12">
                <?=Html::img($user->avatar, ['class'=>'img-circle tall-px5-25 wide-px5-25'])?>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 top-buffer-1">
                <div><label>Último acesso:</label></div>
                        <div><?=!empty($user->last_access_date)?Yii::$app->formatter->asRelativeTime($user->last_access_date):' primeiro acesso'?></div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 top-buffer-4">
                <cite class="strong-6 text-primary"><?=!empty($user->pharse)?'"'. $user->pharse. '"':'"Escreva sua frase"'?></cite>
            </div>
       </div>
    </div>
    <div class="col-lg-6 col-md-9 col-xs-12 top-buffer-4">
        
    
<?php $form = ActiveForm::begin([
             //'action'=>  Url::to(['/bike-keeper/item/create']),
                'id' =>'account-form',
                'options' => ['data' => ['pjax' => true], 'enctype' => 'multipart/form-data', 'id'=>'account-form'],
                'method' => 'post',
                'action' => Url::to('users/update'),
                'fieldConfig' => [
                    'template' => "<div class='row top-buffer-1'><div class=\"col-lg-8 col-xs-12\">{label}</div>\n<div class=\"col-lg-8 col-xs-12\">{input}</div>\n<div class=\"col-lg-4 col-xs-8\">{error}</div></div>",
                    'labelOptions' => ['class' => ''],
                ],
        ]); 
?>

<?=Yii::$app->controller->renderFile('@app/views/users/_form.php', ['user'=>$user, 'form'=>$form])?>

<?php $form->end(); ?>
        </div>  
</div>
