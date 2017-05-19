<?php
use yii\helpers\Url;
use yii\bootstrap\Html;
//Url::to(['alerts/test', 'id'=>$alert->id])
?>

<div class="top-buffer-1" id="nonalert-users-list" >
<?php foreach ($alert->nonExistence as $ane): ?>
  <div class="panel panel-default">
      <div class="panel-body">
          <h4 class="panel-title left-pbuffer-0 right-pbuffer-0">
            <img src="<?=$ane->user->avatar?>" class="img-circle wide-px5-6 tall-px5-6 right-buffer-1"/><span class="text-primary"><strong>@<?=$ane->user->how_to_be_called?></strong></span> <strong><?=Yii::$app->formatter->asRelativeTime($ane->created_date)?></strong>  
          </h4>
          <?php if((!$ane->user->isMyFriend(Yii::$app->user->identity->id)) && !Yii::$app->user->identity->wasIRequestFriendship($ane->user_id)):?>
            <a id='nonalert-<?="$ane->user_id-$ane->alert_id-"?>user-add-btn' data-toggle="tooltip" data-placement="left" title="Solicitar amizade" role="button" class="btn btn-xs btn-default nonalert-user-add pull-right" data-original-title="Solicitar amizade"><span class="text-success glyphicon glyphicon-plus"></span></a>
            <small class="top-buffer-1" style="display:none;"></small>
              <?=Html::hiddenInput((new \app\models\UserFriendshipRequests())->formName().'[requested_user_id]', $ane->user_id);?> 
          <?php elseif(Yii::$app->user->identity->wasIRequestFriendship($ane->user_id)): ?>  
            <a id='nonalert-<?="$ane->user_id-$ane->alert_id-"?>user-add-btn' data-toggle="tooltip" data-placement="right" title="Existe uma solicitação de amizade pendente" role="button" class="btn btn-xs btn-default pull-right" data-original-title="Existe uma solicitação de amizade pendente"><span class="text-very-muted glyphicon glyphicon-user"></span></a>
          <?php endif; ?>
          <?php /* <a id='nonalert-<?=$alert->id?>-btn-disable' data-toggle="tooltip" data-placement="left" title="Desativar alerta" role="button" class="btn btn-xs btn-default nonalert-disable" data-original-title="Desativar alerta"><span class="text-primary glyphicon glyphicon-comment"></span></a> */?>
      </div>
    </div>
<?php endforeach; ?>
</div>