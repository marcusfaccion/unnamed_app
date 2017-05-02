<?php //Tem que fazer a lógica do posicionamento do comentário a direita ou a esquerda ?>

<div class="row">
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
          
</div>

<?php /*<div class="row top-buffer-1">
           <div class=" col-lg-10 col-xs-10 left-buffer-3 bg-info border-radius-3">
  <div>
  	<label>Marcus</label>           
  </div>
  <div>
  Teste de comentário 2.5 uhgsf usufggggsd ghsgf
  </div>
  <div class="pull-right right-buffer-1">
  <i><small class="text-muted">há 11min</small></i>
  </div>
</div>
          
</div>

<div class="row top-buffer-1">
  <div class=" col-lg-10 col-xs-10 left-buffer-9 bg-warning border-radius-3" style="">
  	<div class="text-right">
  		<label>Marcus</label>           
  	</div>
  	<div>
  	Teste de comentário 3 uhgsf usufggggsd ghsgf
  	</div>
  	<div class="pull-left right-buffer-1">
  		<i><small class="text-muted">há 15min</small></i>
  	</div>
  </div>
          
</div>*/?>
       