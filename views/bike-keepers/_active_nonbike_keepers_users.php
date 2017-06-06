<?php
use yii\helpers\Url;
use yii\bootstrap\Html;
?>

<div class="top-buffer-1" id="nonbike-keeper-users-list" >
<?php foreach ($bike_keeper->nonExistence as $bne): ?>
  <div class="panel panel-default">
      <div class="panel-body">
          <h4 class="panel-title left-pbuffer-0 right-pbuffer-0">
            <img src="<?=$bne->user->avatar?>" class="img-circle wide-px5-6 tall-px5-6 right-buffer-1"/><span class="text-primary"><strong><?=$bne->user->how_to_be_called?></strong></span> <strong><?=Yii::$app->formatter->asRelativeTime($bne->created_date)?></strong>  
          </h4>
          <?php if((!$bne->user->isMyFriend(Yii::$app->user->identity->id)) && !Yii::$app->user->identity->wasIRequestFriendship($bne->user_id)):?>
            <a id='non--<?="$bne->user_id-$bne->bike_keeper_id-"?>user-add-btn' data-toggle="tooltip" data-placement="left" title="Solicitar amizade" role="button" class="btn btn-xs btn-default nonbike-keeper-user-add pull-right" data-original-title="Solicitar amizade"><span class="text-success glyphicon glyphicon-plus"></span></a>
            <small class="top-buffer-1" style="display:none;"></small>
              <?=Html::hiddenInput((new \app\models\UserFriendshipRequests())->formName().'[requested_user_id]', $bne->user_id);?> 
          <?php elseif(Yii::$app->user->identity->wasIRequestFriendship($bne->user_id) && !$bne->user->isMyFriend(Yii::$app->user->identity->id)):?>  
            <a id='nonbike-keeper-<?="$bne->user_id-$bne->bike_keeper_id-"?>user-add-btn' data-toggle="tooltip" data-placement="right" title="Existe uma solicitação de amizade pendente" role="button" class="btn btn-xs btn-default pull-right" data-original-title="Existe uma solicitação de amizade pendente"><span class="text-very-muted glyphicon glyphicon-user"></span></a>
          <?php endif; ?>
          <?php /* <a id='nonbike-keeper-<?=$bike_keeper->id?>-btn-disable' data-toggle="tooltip" data-placement="left" title="Desativar bicicletário" role="button" class="btn btn-xs btn-default nonbike-keeper-disable" data-original-title="Desativar bicicletário"><span class="text-primary glyphicon glyphicon-comment"></span></a> */?>
      </div>
    </div>
<?php endforeach; ?>
</div>