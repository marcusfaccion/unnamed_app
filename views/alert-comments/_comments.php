<div class='row hidden'></div>
<?php //Tem que fazer a lógica do posicionamento do comentário a direita ou a esquerda ?>
<?php foreach($alert_comments as $alert_comment):?>

<div class="row top-buffer-1">

<?php if($alert_comment->user_id==Yii::$app->user->identity->id): ?>
    <div class=" col-lg-10 col-xs-10 left-buffer-9 bg-light-green border-radius-3">
      <div class='text-right'>
            <label><?=$alert_comment->user->how_to_be_called?></label>           
      </div>
      <div>
      <?=$alert_comment->text?>
      </div>
      <div class="pull-right right-buffer-1">
          <i><small class="text-muted"><?=Yii::$app->formatter->asRelativeTime($alert_comment->created_date)?></small></i>
      </div>
    </div>
<?php else:?>
    <div class=" col-lg-10 col-xs-10 left-buffer-3 bg-info border-radius-3">
      <div>
            <label><?=$alert_comment->user->how_to_be_called?></label>           
      </div>
      <div>
      <?=$alert_comment->text?>
      </div>
      <div class="pull-right right-buffer-1">
          <i><small class="text-muted"><?=Yii::$app->formatter->asRelativeTime($alert_comment->created_date)?></small></i>
      </div>
    </div>
<?php endif;?>

</div>
<?php endforeach; ?>