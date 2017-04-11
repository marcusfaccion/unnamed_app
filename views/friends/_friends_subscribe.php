<?php
use yii\helpers\Json;
use yii\helpers\Html;
?>
<ul class="list-inline list-unstyled">
<?php foreach($users as $user):?>
    <li class='col-lg-4'>
        <div>
            <?=Html::img($user->avatar, ['class'=>'img-circle wide-px5-8 tall-px5-8'])?>
        </div>
        <div>
            <label><?=$user->full_name?></label>
        </div>
        <div>
            <button class="friends save btn btn-xs btn-primary"><strong>Adicionar</strong></button>
            <span class="hidden"><?=$user->id?></span>
            <?php /*<span class="hidden"><?=Yii::$app->security->encryptByKey($user->id,Yii::$app->security->getAppSecret())?></span>
            <span class="hidden"><?=Yii::$app->security->decryptByKey(Yii::$app->security->encryptByKey($user->id,Yii::$app->security->getAppSecret()),Yii::$app->security->getAppSecret())?></span> */?>
        </div>
    </li>
<?php endforeach;?>
</ul>
<?php if(count($users)==0):?>    
    <div class="alert alert-warning alert-dismissible col-xs-11 col-md-10 col-lg-10" role='alert'>
        <p class='bg-warning text-warning'>
            <strong>Nenhum amigo encontrado!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
        </p>
    </div>
<?php endif;?>    