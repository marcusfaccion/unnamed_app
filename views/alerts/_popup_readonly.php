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
    </div>
</div>
<div class="row top-buffer-1">
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

</div>