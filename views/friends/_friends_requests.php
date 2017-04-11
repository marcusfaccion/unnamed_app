<?php
/* @var $this yii\web\View */
/* @var $user app\models\User */

use yii\bootstrap\Html;
?>
<div class="row">
    <div class="col-xs-12 col-md-12 col-lg-12 top-buffer-2">
          <div id='friendship-request-message-panel'>
              <?php
                if(count($user->friendshipRequests)==0)
                   echo Yii::$app->controller->renderPartial('@app/views/_html_message',['text'=>"Por enquanto não existem solicitações.",'css_class'=>['parent'=>'alert alert-dismissible alert-warning','text'=>'alert-warning']]);
              ?>
          </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-12 col-lg-12 top-buffer-2">
        <ul class="list-inline list-unstyled">
            <?php foreach($user->friendshipRequests as $friendshipRequest):?>
            <li class="top-buffer-2 col-lg-4">
                <div>
                    <?=Html::img($friendshipRequest->requester->avatar, ['class'=>'img-circle wide-px5-8 tall-px5-8'])?>
                </div>
                <div>
                    <label><?=$friendshipRequest->requester->full_name; ?></label> 
                </div>
                <div>
                    <em><?=Yii::$app->formatter->asRelativeTime($friendshipRequest->created_date)?></em>
                </div>
                <div>
                    <button class="friendship-request acept btn btn-xs btn-success"><strong><span class="text-white glyphicon glyphicon-ok-sign"></span> Aceitar</strong></button>
                    <span class="hidden"><?=$friendshipRequest->requester->id?></span>
                    <button class="friendship-request decline btn btn-xs btn-default"><strong><span class="text-danger glyphicon glyphicon-trash"></span> Excluir</strong></button>
                    <?php /*<span class="hidden"><?=Yii::$app->security->encryptByKey($user->id,Yii::$app->security->getAppSecret())?></span>
                    <span class="hidden"><?=Yii::$app->security->decryptByKey(Yii::$app->security->encryptByKey($user->id,Yii::$app->security->getAppSecret()),Yii::$app->security->getAppSecret())?></span> */?>
                </div>
            </li>
            <?php endforeach;?>
        </ul>
    </div>
</div>