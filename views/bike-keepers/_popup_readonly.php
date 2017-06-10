<?php
/**
 *  @var $bike_keeper BikeKeepers model
 */
//use yii\helpers\Url;
use yii\helpers\Html;
?>

<div id="bike-keeper-popup-content-<?=$bike_keeper->id?>" class="bike-keeper-popup">
<?=Html::hiddenInput($bike_keeper->formName().'[id]', $bike_keeper->id, ['id'=>'popup-'.strtolower($bike_keeper->formName()."-$bike_keeper->id")])?>   
<?=Html::hiddenInput($bike_keeper->formName().'[likes]', $bike_keeper->likes, ['id'=>'popup-'.strtolower($bike_keeper->formName()."-likes-$bike_keeper->id")])?>   
<?=Html::hiddenInput($bike_keeper->formName().'[dislikes]', $bike_keeper->dislikes, ['id'=>'popup-'.strtolower($bike_keeper->formName()."-dislikes-$bike_keeper->id")])?>   
<?=Html::hiddenInput($bike_keeper->formName().'[avaliated]', 0, ['id'=>'popup-'.strtolower($bike_keeper->formName()."-avaliated-$bike_keeper->id")])?>   
<div class="row top-buffer-1">
    <div class="col-lg-12 col-xs-12">
        <p class="bg-default text-center"><strong><span class="glyphicon glyphicon-exclamation-sign"></span> <?=$bike_keeper->title?></strong></p>
    </div>
</div>
<?php if($bike_keeper->IsUnreliable):?>
<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
  <strong>Atenção!</strong> <?=count($bike_keeper->nonExistence)?> <?=Yii::t('app', '{n,plural, =1{usuário reportou} other{usuários reportaram}}',['n'=>count($bike_keeper->nonExistence)])?> que esse bicicletário não existe mais.
</div>
<?php endif;?>
<div class="row">
    <div class="col-lg-12 col-xs-12">
        <div class="col-lg-3 col-xs-3">
            <label>Funcinando desde:</label>
            <?=Yii::$app->formatter->asDate($bike_keeper->created_date, 'Y')?>
        </div>
        <div class="col-lg-3 col-xs-3 right-pbuffer-0 left-pbuffer-0">
            <div class="col-lg-12 col-xs-12 right-pbuffer-0">
                <label>Horário:</label>
            </div>
            <div class="col-lg-12 col-xs-12 right-pbuffer-0">
                <?=$bike_keeper->business_hours?>
            </div>
        </div>
        <div class="col-lg-4 col-xs-4 right-pbuffer-0 left-pbuffer-1">
            <div class="col-lg-12 col-xs-12 left-pbuffer-0 right-pbuffer-0">
                <label>Por:</label>
            </div>
            <div class="col-lg-12 col-xs-12 left-pbuffer-0 right-pbuffer-0">
                <?=$bike_keeper->user->how_to_be_called?>
            </div>
        </div>
        <div class="col-lg-2 col-xs-2 right-pbuffer-0 left-pbuffer-0">
            <?=Html::img($bike_keeper->user->avatar, ['class'=>'img-circle wide-px5-7  tall-px5-7'])?>
        </div>
    </div>
</div>
<?php if(!empty(trim($bike_keeper->address))): ?>
<div class="row">
<div class="col-lg-12 col-xs-12 left-pbuffer-0 top-buffer-1">
    <div class="col-lg-12 col-xs-12 left-pbuffer-0 right-pbuffer-0">   
        <div class="col-lg-3 col-xs-2">
            <label>Endereço:</label>
        </div>
       <div class="left-pbuffer-6 col-lg-9 col-xs-9">
            <?=$bike_keeper->address?>
        </div>
    </div>
</div>
</div>
<?php endif; ?>

<?php if(!empty($bike_keeper->tel)||!empty($bike_keeper->email)):?>
<div class="row top-buffer-1 col-lg-12 col-xs-12">
        <?php if(!empty($bike_keeper->tel)):?>
        <div class="col-lg-5 col-xs-5 left-pbuffer-0 right-pbuffer-0">
            <div class="col-lg-12 col-xs-12">
                <label>Telefone:</label>
            </div>
            <div class="col-lg-12 col-xs-12">
                <?=$bike_keeper->tel?>
            </div>
        </div>
        <?php endif;?>
      <?php if(!empty($bike_keeper->email)):?>
        <div class="col-lg-6 col-xs-6 left-pbuffer-0 right-pbuffer-0">
            <div class="col-lg-12 col-xs-12">
                <label>Email:</label>
            </div>
            <div class="col-lg-12 col-xs-12">
                <?=$bike_keeper->email?>
            </div>
        </div>
        <?php endif;?>
</div>    
<?php endif;?>
<div class="row">
<div class="top-buffer-1 col-lg-12 col-xs-12">
    <div class="col-lg-6 col-xs-6">
        <div>
            <label>Número de vagas:</label>
        </div>
        <div>
            <i><?=$bike_keeper->capacity?></i>
        </div>
    </div>
    <?php if(!$bike_keeper->public):?>
    <div class="col-lg-6 col-xs-6">
        <div>
            <label>Vagas disponíveis:</label>
        </div>
        <div>
            <i><?=$bike_keeper->capacity-$bike_keeper->used_capacity?></i>
        </div>
    </div>
    <?php endif;?>
</div>
</div>
<?php //Data da última atualização de informações do bicicletário ?>
<?php if(!empty($bike_keeper->updated_date)||count($bike_keeper->multimedias)>0):?>
<div class="row top-buffer-3 col-lg-12 col-xs-12">
    <?php if(count($bike_keeper->multimedias)>0): ?>
    <div class="col-lg-6 col-xs-6">   
            <div>
                <span data-toggle="tooltip" title="Veja as fotos do bicicletário">
                <a class="btn btn-default bike-keeper-photos" data-toggle='modal' data-target='#bike_keepers_photos_modal'><span class="glyphicon glyphicon-picture tsize-10"></label></a>
                </span>
                <?=yii\bootstrap\Html::hiddenInput($bike_keeper->formName().'[id]', $bike_keeper->id, ['id'=>'bike-keeper-'.str_shuffle(rand(0, 99))])?>
            </div>
        <div class="left-pbuffer-1">
                <small class="text-primary">Fotos</small>
            </div>
    </div>    
    <?php endif; ?>
<?php if(!empty($bike_keeper->updated_date)):?>
    <div class="col-lg-6 col-xs-6">   
        <div>
            <label class="text-danger">Atuallizado:</label>
        </div>
        <div>
            <i class="text-danger"><?=Yii::$app->formatter->asRelativeTime($bike_keeper->updated_date)?></i>
        </div>
    </div>    
<?php endif;?>
</div>
<?php endif;?>

    
<div class="row top-buffer-2 col-lg-12 col-xs-12 left-pbuffer-0 left-buffer-0">
    <?php if(!$bike_keeper->public):?>
    <div class="left-pbuffer-1 left-buffer-2 border-radius-3 bg-info">
        <div class="text-center">
            <label class="text-primary">Bicicletário Privado</label>
        </div>
        <div class="text-center">
            <strong class="tsize-4">Diária: <?=Yii::$app->formatter->asCurrency($bike_keeper->cost)?></strong>
        </div>
        <div class="text-center">
            <small class="strong-6"><?=$bike_keeper->outdoor?'(bicicletário coberto)':'(bicicletário ao ar livre)'?></small>
        </div>
    </div>
    <?php else:?>
    <div class="left-pbuffer-1 left-buffer-2 border-radius-3 bg-light-green top-pbuffer-1">
        <div class="text-center">
            <label class="text-success tsize-4">Bicicletário Público</label>
        </div>
        <div class="text-center">
            <small class="strong-6"><?=$bike_keeper->outdoor?'(bicicletário coberto)':'(bicicletário ao ar livre)'?></small>
        </div>
    </div>
    <?php endif;?>
</div>

<?php if(!empty($bike_keeper->description)):?>
<div class="row">        
    <div class="top-buffer-3 left-pbuffer-0 col-lg-12 col-xs-12">
        <div class="col-lg-3 col-xs-3">
            <label>Descrição:</label>
        </div>
        <div class="col-lg-9 col-xs-9">
            <i><?=$bike_keeper->description?></i>
        </div>
    </div>
</div>
<?php endif;?>
<div class="row">
    <div class="col-lg-12 col-xs-12 top-buffer-1">
        <div class="left-pbuffer-0 right-buffer-minus2 col-lg-3 col-xs-3">
            <a role="button" class="btn btn-md bike-keeper-like tsize-4 disabled"><span class="glyphicon glyphicon-thumbs-up text-success"></span> <span class="text-success badge"><?=$bike_keeper->likes?></span></a> 
            <small class="text-muted col-xs-offset-5">Util!</small>
            <input type="hidden" class="btn-classes bike-keeper-like hidden" value="btn btn-md bike-keeper-like tsize-4">
            <input type="hidden" class="small-classes bike-keeper-like hidden" value="text-success col-xs-offset-5">
        </div>
        <div class="left-pbuffer-0 right-buffer-minus4 col-lg-3 col-xs-3">
            <a role="button" class="btn btn-md bike-keeper-dislike tsize-4 disabled"><span class="glyphicon glyphicon-thumbs-down text-danger"></span> <span class="text-danger badge"><?=$bike_keeper->dislikes?></span></a> 
            <small class="text-muted col-xs-offset-3">Não Util!</small>
            <input type="hidden" class="btn-classes bike-keeper-dislike hidden" value="btn btn-md bike-keeper-dislike tsize-4">
            <input type="hidden" class="small-classes bike-keeper-dislike hidden" value="text-danger col-xs-offset-3">
        </div>
        <input type="hidden"  class="hidden bike-keeper-id" value="<?=$bike_keeper->id?>">
    </div>
</div>
<?php //Comentários ?>
<div class="row">
<div class="top-buffer-1 popup-comment-header">
    <div class="col-lg-12 col-xs-12">
        <p class="bg-primary text-white text-center comment-counter"><strong><span class="glyphicon glyphicon glyphicon-comment"></span> Comentários <span class="text-white badge"><?=count($bike_keeper->comments)>0?count($bike_keeper->comments):null?></span></strong></p>
    </div>
</div>
</div>
<div class="row">        
<div class="row popup-comment-container">
    <div class="col-lg-12 col-xs-12 wrapper">
        <?php echo Yii::$app->controller->renderPartial('@app/views/bike-keeper-comments/_comments', ['bike_keeper_comments'=>$bike_keeper->comments]);?>
    </div>
</div>    
</div>
    
</div>