<div class='row hidden'></div>
<?php //Tem que fazer a lógica do posicionamento do comentário a direita ou a esquerda ?>
<?php
$i = 0;
$comment_history = []; 
?>
<?php foreach($alert_comments as $alert_comment):?>
    <?php //log de data dos comentários ?>
    <?php $comment_history[$i] = $alert_comment; ?>
    <?php if($i>0 && $comment_history[$i-1]): ?>
        <?php if((int)Yii::$app->formatter->asDate($comment_history[$i-1]->created_date, 'dd') < (int)Yii::$app->formatter->asDate($alert_comment->created_date, 'dd')): ?>
            <div class="row top-buffer-1">
                <div class="col-lg-offset-6 col-xs-offset-6">
                    <span class="text-muted"><i><?=Yii::$app->formatter->asDate($alert_comment->created_date)?></i></span>
                </div>
            </div>
        <?php endif;?>
    <?php endif;?>
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
<?php ++$i; ?>
<?php endforeach; ?>