<?php
use yii\helpers\Html;
?>
<?php if(count($conversations)>0):?>
<div class='row hidden'></div>
<?php //Tem que fazer a lógica do posicionamento da conersa a direita ou a esquerda ?>
<?php
$i = 0;
$conversation_history = []; 
?>
<?php foreach($conversations as $conversation):?>
    <?php //log de data das mensagens ?>
    <?php $conversation_history[$i] = $conversation; ?>
    <?php if($i>0 && $conversation_history[$i-1]): ?>
        <?php if((int)Yii::$app->formatter->asDate($conversation_history[$i-1]->created_date, 'dd') < (int)Yii::$app->formatter->asDate($conversation->created_date, 'dd')): ?>
            <div class="row top-buffer-1">
                <div class="col-lg-offset-6 col-xs-offset-6 text-left">
                    <span class="text-muted"><i><?=(Yii::$app->formatter->asDate(date('Y-m-d'))==Yii::$app->formatter->asDate($conversation->created_date)?'Hoje':Yii::$app->formatter->asDate($conversation->created_date))?></i></span>
                </div>
            </div>
        <?php endif;?>
    <?php endif;?>
<div class="row top-buffer-1">
<?php if($conversation->user_id==Yii::$app->user->identity->id): ?>
    <div class=" col-lg-10 col-xs-10 left-buffer-9 bg-light-green border-1 box-shadow-1 border-radius-3">
      <div class='text-right'>
            <label class="text-success"><?=$conversation->user->how_to_be_called?></label>           
      </div>
      <div class='text-left tsize-4'>
      <?=$conversation->text?>
      </div>
      <div class="pull-right right-buffer-1">
          <i><small class="text-muted"><?=Yii::$app->formatter->asRelativeTime($conversation->created_date)?></small></i>
      </div>
    </div>
<?php else:?>
    <div class=" col-lg-10 col-xs-10 left-buffer-3 bg-info border-1 box-shadow-1 border-radius-3">
        <div class="text-right">
            <label class="text-primary"><?=$conversation->user->how_to_be_called?></label>           
      </div>
      <div class='text-left tsize-4'>
      <?=$conversation->text?>
      </div>
      <div class="pull-right right-buffer-1">
          <i><small class="text-muted"><?=Yii::$app->formatter->asRelativeTime($conversation->created_date)?></small></i>
      </div>
    </div>
<?php endif;?>
</div>
<?php ++$i; ?>
<?php endforeach; ?>
<?php else: ?>
<h2 class="text-muted"><cite>Envie uma mensagem para <?=$user2->how_to_be_called?>.</cite></h2>
<?php endif; ?>