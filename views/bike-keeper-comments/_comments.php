<div class='row hidden'></div>
<?php //Tem que fazer a lógica do posicionamento do comentário a direita ou a esquerda ?>
<?php
$i = 0;
$comment_history = []; 
?>
<?php foreach($bike_keeper_comments as $bike_keeper_comment):?>
    <?php //log de data dos comentários ?>
    <?php $comment_history[$i] = $bike_keeper_comment; ?>
    <?php if($i>0 && $comment_history[$i-1]): ?>
        <?php if(strtotime(Yii::$app->formatter->asDate($comment_history[$i-1]->created_date)) < strtotime(Yii::$app->formatter->asDate($bike_keeper_comment->created_date))): ?>
            <div class="row top-buffer-1">
                <div class="col-lg-offset-6 col-xs-offset-6">
                    <span class="text-muted"><i><?=(Yii::$app->formatter->asDate(date('Y-m-d'))==Yii::$app->formatter->asDate($bike_keeper_comment->created_date)?'Hoje':Yii::$app->formatter->asDate($bike_keeper_comment->created_date))?></i></span>
                </div>
            </div>
        <?php endif;?>
    <?php endif;?>

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
<?php ++$i; ?>
<?php endforeach; ?>