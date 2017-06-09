<?php
//use Yii;
?>
<?php if(in_array($user_feeding->userSharing->type->name,['alert'])):?>
<div class="row">
    <div class='col-xs-12 col-md-12 col-lg-12'>
        <h4 class='text-primary strong-6 top-buffer-2'>Resumo do <?=Yii::t('app', $user_feeding->userSharing->type->name)?> <span class="glyphicon glyphicon-list-alt"></span></h4>
    </div>
    <div class='col-xs-12 col-md-12 col-lg-12'>
        <ul class="list-unstyled">
            <li>
                <label>Estado:</label> <span class="left-buffer-1 text-muted"><?=$content->enable?'ativo':'desativado'?></span>
            </li>
        </ul>
    </div>
</div>
<?php endif;?>
<?php if(in_array($user_feeding->userSharing->type->name,['bike keeper'])):?>
<div class="row">
    <div class='col-xs-12 col-md-12 col-lg-12'>
        <h4 class='text-primary strong-6 top-buffer-2'>Resumo do <?=Yii::t('app', $user_feeding->userSharing->type->name)?> <span class="glyphicon glyphicon-list-alt"></span></h4>
    </div>
    <div class='col-xs-12 col-md-12 col-lg-12'>
        <ul class="list-unstyled">
           <li>
                <label>Estado:</label> <span class="left-buffer-1 text-muted"><?=$content->enable?'ativo':'desativado'?></span>
            </li>
        </ul>
    </div>
</div>
<?php endif;?>
<?php if(in_array($user_feeding->userSharing->type->name,['route'])):?>
<div class="row">
    <div class='col-xs-12 col-md-12 col-lg-12'>
        <h4 class='text-primary strong-6 top-buffer-2'>Resumo de <?=Yii::t('app', $user_feeding->userSharing->type->name)?> <span class="glyphicon glyphicon-list-alt"></span></h4>
    </div>
    <div class='col-xs-12 col-md-12 col-lg-12'>
        <ul class="list-unstyled">
            <li>
                <label>Endereço da partida:</label> <span class="left-buffer-1 text-muted"><?=$content->origin_address?></span>
            </li>
            <li>
                <label>Endereço de chegada:</label> <span class="left-buffer-1 text-muted"><?=$content->destination_address?></span>
            </li>
            <li>
                <label>Distância percorrida:</label> <span class="left-buffer-1 text-muted"><?=round($content->distance,2)?>m</span>
            </li>
            <li>
                <label>Duração:</label> <span class="left-buffer-1 text-muted"><?=Yii::$app->formatter->asDuration($content->duration, true)?></span>
            </li>
        </ul>
    </div>
    <?php if(!empty(trim($user_feeding->text))):?>
    <div class='col-xs-12 col-md-12 col-lg-12'>
        <h4 class='text-primary strong-6 top-buffer-2'>Mensagem de <?=$content->user->how_to_be_called?> <span class="glyphicon glyphicon-comment"></span></h4>
        <p class="text-muted"><?=$user_feeding->text?></p>
    </div>
    <?php endif;?>
</div>
<?php endif;?>