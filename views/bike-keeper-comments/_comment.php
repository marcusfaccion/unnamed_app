<div class="row top-buffer-1">

<?php if($bike_keeper_comment->user_id==Yii::$app->user->identity->id): ?>
    <div class=" col-lg-10 col-xs-10 left-buffer-9 bg-light-green border-radius-3">
      <div class='text-right'>
            <label class="text-success"><?=$bike_keeper_comment->user->how_to_be_called?></label>           
      </div>
      <div>
      <?=$bike_keeper_comment->text?>
      </div>
      <div class="pull-right right-buffer-1">
          <i><small class="text-muted"><?=Yii::$app->formatter->asRelativeTime($bike_keeper_comment->created_date)?></small></i>
      </div>
    </div>
<?php else:?>
    <div class=" col-lg-10 col-xs-10 left-buffer-3 bg-info border-radius-3">
      <div>
            <label class="text-primary"><?=$bike_keeper_comment->user->how_to_be_called?></label>           
      </div>
      <div>
      <?=$bike_keeper_comment->text?>
      </div>
      <div class="pull-right right-buffer-1">
          <i><small class="text-muted"><?=Yii::$app->formatter->asRelativeTime($bike_keeper_comment->created_date)?></small></i>
      </div>
    </div>
<?php endif;?>

</div>
       