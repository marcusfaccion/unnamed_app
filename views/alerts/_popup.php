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
        <div class="col-lg-3 col-xs-3">
            <label>Avisado:</label>
            <?=Yii::$app->formatter->asRelativeTime($alert->created_date)?>
        </div>
        <div class="col-lg-3 col-xs-3 right-pbuffer-0">
            <div class="col-lg-12 col-xs-12">
                <label>Por:</label>
            </div>
            <div class="col-lg-12 col-xs-12">
                <?=$alert->user->how_to_be_called?>
            </div>
        </div>
        <div class="col-lg-5 col-xs-5 right-pbuffer-0">
            <?=Html::img($alert->user->avatar, ['class'=>'img-circle wide-px5-7  tall-px5-7'])?>
        </div>
        <?php if(!empty(trim($alert->address))): ?>
        <div class="col-lg-12 col-xs-12 left-pbuffer-0 top-buffer-1">
            <div class="col-lg-12 col-xs-12 left-pbuffer-0 right-pbuffer-0">   
                <div class="col-lg-3 col-xs-2">
                    <label>Endereço:</label>
                </div>
                <div class="left-pbuffer-6 col-lg-9 col-xs-9">
                    <?=$alert->address?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <div class="col-lg-12 col-xs-12 left-pbuffer-0 top-buffer-1">
        <?php if($alert->duration_date):?>
            <div class="col-lg-4 col-xs-12 left-pbuffer-0 right-pbuffer-0">   
                <div class="col-lg-12 col-xs-12">
                    <label>Expira em:</label>
                </div>
                <div class="col-lg-12 col-xs-12">
                    <?php 
                        $timezone = Yii::$app->formatter->timeZone;
                        Yii::$app->formatter->timeZone = 'UTC';
                        echo Yii::t('app', Yii::$app->formatter->asDuration(str_replace('+00:00', 'Z', Yii::$app->formatter->format(date('Y-m-d H:i:s'),['datetime','php:c'])).'/'.str_replace('+00:00', 'Z', Yii::$app->formatter->asDatetime($alert->duration_date,'php:c')), 1));
                        Yii::$app->formatter->timeZone = $timezone;
                    ?>
                    <?php // Yii::$app->formatter->asDatetime($alert->duration_date)?>
                </div>
            </div>
            <div class="col-lg-8 col-xs-12 left-pbuffer-0">
        <?php else:?>
            <div class="col-lg-8 col-xs-12">
        <?php endif;?>
            
                <div class="col-lg-12 col-xs-12 left-pbuffer-0">
                    <label>Mensagem:</label>
                </div>
                <div class="col-lg-12 col-xs-12 left-pbuffer-0">
                    <i><?=$alert->description?></i>
                </div>
            </div>
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
                <div class="left-pbuffer-0 col-lg-3 col-xs-3">
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
                <div class="left-pbuffer-0 col-lg-3 col-xs-3">
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
                <div class="left-pbuffer-0 col-lg-3 col-xs-3">
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
                <div class="left-pbuffer-0 col-lg-3 col-xs-3">
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
                <div class="left-pbuffer-0 col-lg-3 col-xs-3">
                    <a role="button" class="btn btn-md dislike tsize-4"><span class="glyphicon glyphicon-thumbs-down text-danger"></span> <span class="text-danger badge"><?=$alert->dislikes?></span></a> 
                    <small class="text-danger col-xs-offset-3">Não Util!</small>
                    <input type="hidden" class="btn-classes dislike hidden" value="btn btn-md dislike tsize-4">
                    <input type="hidden" class="small-classes dislike hidden" value="text-danger col-xs-offset-3">
                </div>
        <?php endif;?>
        
        <input type="hidden"  class="hidden alert-id" value="<?=$alert->id?>">
        <div class="left-pbuffer-0 col-lg-6 col-xs-6 top-buffer-1">
            <?php if($alert->user_id==Yii::$app->user->identity->id): ?>
                <a role="button" class="btn btn-danger btn-xs alert-disable"><span class="glyphicon glyphicon glyphicon-off text-white tsize-4"></span> <strong><span class="text-white"> Desativar Alerta!</span></strong></a> 
                <a role="button" class="btn btn-danger btn-xs alert-delete hidden"><span class="glyphicon glyphicon glyphicon-trash text-white tsize-4"></span> <strong><span class="text-white"> Excluir Alerta!</span></strong></a> 
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
<div class="row top-buffer-1 popup-comment-header">
    <div class="col-lg-12 col-xs-12">
        <p class="bg-primary text-white text-center comment-counter"><strong><span class="glyphicon glyphicon glyphicon-comment"></span> Comentários <span class="text-white badge"><?=count($alert->comments)>0?count($alert->comments):null?></span></strong></p>
    </div>
</div>
    
<div class="row popup-comment-container">
    <div class="col-lg-12 col-xs-12 wrapper">
        <?php echo Yii::$app->controller->renderPartial('@app/views/alert-comments/_comments', ['alert_comments'=>$alert->comments]);?>
    </div>
</div>

<div class="row top-buffer-3">
    <div class="col-lg-12 col-xs-12">
      <label>Deixe seu comentário:</label>
      <small class="text-success display-block" style="display:none;">Comentário enviado com sucesso!</small>
      <small class="text-danger display-block" style="display:none;">Escreva o comentário.</small>
    </div>
    <div class="col-lg-12 col-xs-12">
        <?=Html::textarea('AlertComments[text]', '', ["id"=>"alert-comments-{$alert->id}","class"=>"form-control alert-comment","placeholder"=>"Escreva aqui seu comentário", 'rows'=>3])?>
    </div>
    <div class="col-lg-12 col-xs-12 top-buffer-1">
        <?=Html::button("Comentar <span class='glyphicon glyphicon-ok-circle tsize-4'></span>",['class'=>'btn btn-xs btn-success alert-comment', 'data-loading-text'=>'Enviando...', 'autocomplete'=>'off'])?>
    </div>
</div>    
    
</div>