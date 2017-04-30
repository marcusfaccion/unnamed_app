<?php
/**
 *  @var $alert Alert model
 */
//use yii\helpers\Url;
use yii\helpers\Html;
?>

<div id="alert-popup-content-<?=$alert->id?>" class="alert-popup">
<?=Html::hiddenInput($alert->formName().'[id]', $alert->id, ['id'=>'popup-'.strtolower($alert->formName()."-$alert->id")])?>   
<?=Html::hiddenInput($alert->formName().'[likes]', $alert->likes, ['id'=>'popup-'.strtolower($alert->formName()."-likes-$alert->id")])?>   
<?=Html::hiddenInput($alert->formName().'[dislikes]', $alert->dislikes, ['id'=>'popup-'.strtolower($alert->formName()."-dislikes-$alert->id")])?>   
<?=Html::hiddenInput($alert->formName().'[avaliated]', 0, ['id'=>'popup-'.strtolower($alert->formName()."-avaliated-$alert->id")])?>   
<div class="row top-buffer-1">
    <div class="col-lg-12 col-xs-12">
        <p class="bg-default text-center"><strong><span class="glyphicon glyphicon-exclamation-sign"></span> <?=$alert->type->description?></strong></p>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-xs-12">
        <div class="col-lg-6 col-xs-6">
            <label>Avisado:</label>
            <?=Yii::$app->formatter->asRelativeTime($alert->created_date)?>
        </div>
        <div class="col-lg-6 col-xs-6">
            <label>Por:</label>
            <?=$alert->user->how_to_be_called?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-xs-12">
        <div class="col-lg-3 col-xs-3">
            <label>Mensagem:</label>
        </div>
        <div class="col-lg-9 col-xs-9">
            <i><?=$alert->description?></i>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-xs-12">
        <?php if($user_avaliation && !$user_alert_existence): ?>
            <?php if($user_avaliation->rating=='likes'): ?>
                <div class="col-lg-3 col-xs-3">
                    <a role="button" class="btn btn-md like tsize-4"><span class="glyphicon glyphicon-thumbs-up text-success"></span> <span class="text-success badge"><?=$alert->likes?></span></a> 
                    <small class="text-success col-xs-offset-5">Util!</small>
                    <input type="hidden" class="btn-classes like hidden" value="btn btn-md like tsize-4">
                    <input type="hidden" class="small-classes like hidden" value="text-success col-xs-offset-5">
                </div>
                <div class="col-lg-3 col-xs-3">
                    <a role="button" class="btn btn-md dislike tsize-4 disabled"><span class="glyphicon glyphicon-thumbs-down text-danger"></span> <span class="text-danger badge"><?=$alert->dislikes?></span></a> 
                    <small class="text-muted col-xs-offset-3">Não Util!</small>
                    <input type="hidden" class="btn-classes dislike hidden" value="btn btn-md dislike tsize-4 disabled">
                    <input type="hidden" class="small-classes dislike hidden" value="text-muted col-xs-offset-3">
                </div>
            <?php endif;?>
            <?php if($user_avaliation->rating=='dislikes'): ?>
                <div class="col-lg-3 col-xs-3">
                    <a role="button" class="btn btn-md like tsize-4 disabled"><span class="glyphicon glyphicon-thumbs-up text-success"></span> <span class="text-success badge"><?=$alert->likes?></span></a> 
                    <small class="text-muted col-xs-offset-5">Util!</small>
                    <input type="hidden" class="btn-classes like hidden" value="btn btn-md like tsize-4 disabled">
                    <input type="hidden" class="small-classes like hidden" value="text-muted col-xs-offset-5">
                </div>
                <div class="col-lg-3 col-xs-3">
                    <a role="button" class="btn btn-md dislike tsize-4"><span class="glyphicon glyphicon-thumbs-down text-danger"></span> <span class="text-danger badge"><?=$alert->dislikes?></span></a> 
                    <small class="text-danger col-xs-offset-3">Não Util!</small>
                    <input type="hidden" class="btn-classes dislike hidden" value="btn btn-md dislike tsize-4">
                    <input type="hidden" class="small-classes dislike hidden" value="text-danger col-xs-offset-3">
                </div>
            <?php endif;?>
        
        
        <?php elseif($user_avaliation && $user_alert_existence):?>
            <?php if($user_avaliation->rating=='likes'): ?>
                <div class="col-lg-3 col-xs-3">
                    <a role="button" class="btn btn-md like tsize-4 disabled"><span class="glyphicon glyphicon-thumbs-up text-success"></span> <span class="text-success badge"><?=$alert->likes?></span></a> 
                    <small class="text-muted col-xs-offset-5">Util!</small>
                    <input type="hidden" class="btn-classes like hidden" value="btn btn-md like tsize-4">
                    <input type="hidden" class="small-classes like hidden" value="text-success col-xs-offset-5">
                </div>
                <div class="col-lg-3 col-xs-3">
                    <a role="button" class="btn btn-md dislike tsize-4 disabled"><span class="glyphicon glyphicon-thumbs-down text-danger"></span> <span class="text-danger badge"><?=$alert->dislikes?></span></a> 
                    <small class="text-muted col-xs-offset-3">Não Util!</small>
                    <input type="hidden" class="btn-classes dislike hidden" value="btn btn-md dislike tsize-4 disabled">
                    <input type="hidden" class="small-classes dislike hidden" value="text-muted col-xs-offset-3">
                </div>
            <?php endif;?>
            <?php if($user_avaliation->rating=='dislikes'): ?>
                <div class="col-lg-3 col-xs-3">
                    <a role="button" class="btn btn-md like tsize-4 disabled"><span class="glyphicon glyphicon-thumbs-up text-success"></span> <span class="text-success badge"><?=$alert->likes?></span></a> 
                    <small class="text-muted col-xs-offset-5">Util!</small>
                    <input type="hidden" class="btn-classes like hidden" value="btn btn-md like tsize-4 disabled">
                    <input type="hidden" class="small-classes like hidden" value="text-muted col-xs-offset-5">
                </div>
                <div class="col-lg-3 col-xs-3">
                    <a role="button" class="btn btn-md dislike tsize-4 disabled"><span class="glyphicon glyphicon-thumbs-down text-danger"></span> <span class="text-danger badge"><?=$alert->dislikes?></span></a> 
                    <small class="text-muted col-xs-offset-3">Não Util!</small>
                    <input type="hidden" class="btn-classes dislike hidden" value="btn btn-md dislike tsize-4">
                    <input type="hidden" class="small-classes dislike hidden" value="text-danger col-xs-offset-3">
                </div>
            <?php endif;?>
        
        <?php else:?>
                <div class="col-lg-3 col-xs-3">
                    <a role="button" class="btn btn-md like tsize-4"><span class="glyphicon glyphicon-thumbs-up text-success"></span> <span class="text-success badge"><?=$alert->likes?></span></a> 
                    <small class="text-success col-xs-offset-5">Util!</small>
                    <input type="hidden" class="btn-classes like hidden" value="btn btn-md like tsize-4">
                    <input type="hidden" class="small-classes like hidden" value="text-success col-xs-offset-5">
                </div>
                <div class="col-lg-3 col-xs-3">
                    <a role="button" class="btn btn-md dislike tsize-4"><span class="glyphicon glyphicon-thumbs-down text-danger"></span> <span class="text-danger badge"><?=$alert->dislikes?></span></a> 
                    <small class="text-danger col-xs-offset-3">Não Util!</small>
                    <input type="hidden" class="btn-classes dislike hidden" value="btn btn-md dislike tsize-4">
                    <input type="hidden" class="small-classes dislike hidden" value="text-danger col-xs-offset-3">
                </div>
        <?php endif;?>
        
        <input type="hidden"  class="hidden alert-id" value="<?=$alert->id?>">
        <div class="col-lg-6 col-xs-6">
            <?php if($alert->user_id==Yii::$app->user->identity->id): ?>
                <a role="button" class="btn btn-danger btn-sm alert-disable"><span class="glyphicon glyphicon glyphicon-off text-white tsize-4"></span> <strong><span class="text-white"> Desativar Alerta!</span></strong></a> 
                <a role="button" class="btn btn-danger btn-sm alert-delete hidden"><span class="glyphicon glyphicon glyphicon-trash text-white tsize-4"></span> <strong><span class="text-white"> Excluir Alerta!</span></strong></a> 
            <?php elseif($user_alert_existence): ?>
                <a role="button" class="btn btn-success btn-xs alert-exists"><span class="glyphicon glyphicon-ok-circle text-white tsize-4"></span> <strong><span class="text-white"> Alerta existente!</span></strong></a> 
                <a role="button" class="btn btn-danger btn-xs alert-notexists" style="display: none;"><span class="glyphicon glyphicon-remove-circle text-white tsize-4"></span> <strong><span class="text-white"> Alerta inexistente!</span></strong></a> 
            <?php else: ?>
                <a role="button" class="btn btn-success btn-xs alert-exists" style="display: none;"><span class="glyphicon glyphicon-ok-circle text-white tsize-4"></span> <strong><span class="text-white"> Alerta existente!</span></strong></a> 
                <a role="button" class="btn btn-danger btn-xs alert-notexists"><span class="glyphicon glyphicon-remove-circle text-white tsize-4"></span> <strong><span class="text-white"> Alerta inexistente!</span></strong></a> 
            <?php endif; ?>
            <small class="text-success" style="display: none;">Informação enviada!</small>
        </div>
    </div>
</div>
<?php //Comentários ?>
<div class="row top-buffer-1">
    <div class="col-lg-12 col-xs-12">
        <p class="bg-primary text-white text-center"><strong><span class="glyphicon glyphicon glyphicon-comment"></span> Comentários</strong></p>
    </div>
</div>
    
<div class="row popup-comment-container">
    <div class="col-lg-12 col-xs-12">
        <div class="row">
           <div class=" col-lg-10 col-xs-10 left-buffer-3 bg-info border-radius-3">
  <div>
  	<label>Marcus</label>           
  </div>
  <div>
  Teste de comentário 2 uhgsf usufggggsd ghsgf
  </div>
  <div class="pull-right right-buffer-1">
  <i><small class="text-muted">há 10min</small></i>
  </div>
</div>
          
      </div><div class="row top-buffer-1">
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
          
</div>
        
      
    </div>
</div>

<div class="row top-buffer-3">
    <div class="col-lg-12 col-xs-12">
      <label>Deixe seu comentário:</label>  
    </div>
    <div class="col-lg-12 col-xs-12">
        <?=Html::textarea('AlertComent[text]', '', ['class'=>'form-control', 'rows'=>3])?>
    </div>
    <div class="col-lg-12 col-xs-12 top-buffer-1">
        <?=Html::button("Comentar <span class='glyphicon glyphicon-ok-circle tsize-4'></span>",['class'=>'btn btn-sm btn-success alert-comment', 'data-loading-text'=>'Enviando...', 'autocomplete'=>'off'])?>
    </div>
</div>    
    
</div>