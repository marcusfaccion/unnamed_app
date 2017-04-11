<?php
/* @var $this yii\web\View */
/* @var $user app\models\Users */
use yii\helpers\Html;
?>

<div class="row">
    <div class="col-xs-12 col-md-12 col-lg-12 top-buffer-2">
          <div id='friend-list-message-panel'>
              <?php
                if(count($user->friends)==0)
                   echo Yii::$app->controller->renderPartial('@app/views/_html_message',['text'=>"Você ainda não possui amigos.",'css_class'=>['parent'=>'alert alert-dismissible alert-warning','text'=>'alert-warning']]);
              ?>
          </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-12 col-lg-12 top-buffer-3">
        <ul class="list-inline list-unstyled">
        <?php foreach($user->friends as $friend):?>
            <li class="top-buffer-2 col-lg-4">
                <div>
                    <?=Html::img($friend->avatar, ['class'=>'img-circle wide-px5-8 tall-px5-8'])?>
                </div>
                <div>
                    <label><?=$friend->full_name; ?></label> 
                </div>
                <div>
                    <button class="friend-list delete btn btn-xs btn-default"><strong><span class="text-danger glyphicon glyphicon-trash"></span> Excluir</strong></button>
                    <?php /* para usar com confirmação
                     *  <button class="friend-list delete btn btn-xs btn-default" onclick="confirm_message='Deseja excluir esse amigo?'" data-toggle="modal" data-target="#home_actions_confirm_modal"><strong><span class="text-danger glyphicon glyphicon-trash"></span> Excluir</strong></button> 
                     */?>
                    <span class="hidden"><?=$friend->id?></span>
                    <?php /*<span class="hidden"><?=Yii::$app->security->encryptByKey($user->id,Yii::$app->security->getAppSecret())?></span>
                    <span class="hidden"><?=Yii::$app->security->decryptByKey(Yii::$app->security->encryptByKey($user->id,Yii::$app->security->getAppSecret()),Yii::$app->security->getAppSecret())?></span> */?>
                </div>
            </li>
        <?php endforeach;?>
        </ul>
    </div>
</div>
