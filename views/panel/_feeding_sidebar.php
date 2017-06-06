<?php
use yii\helpers\Html;
use app\models\UserFeedings;
?>
<?php $gender = ''; ?>
<?php if(1):?>
<div id='user-feeding-placeholder'>
    <ul id='user-feeding-list' class="list-unstyled hover">
        
        <?php //<li id='user-feeding-first-item' style='display:none;'></li> ?>
        
        <?php foreach($feedings as $feed):?>
        <?php
            if(in_array($feed->userSharing->type->name, ['route'])){ // somente rota é um nome feminio em pt-BR alerta e bicicletário não 
                 $gender = 'female';
            }else{
                 $gender = 'other';
            }
        ?>
        <li id='user-feeding-<?=$feed->id?>' class="messages-friend border-radius-2 border-1 left-buffer-1 top-buffer-2 bottom-buffer-1 box-shadow-1">
        <div class="top-buffer-2">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <label class="left-buffer-1">
                        <img class='img-circle wide-px5-4 tall-px5-4' src="<?=$feed->user->avatar?>">
                        <span class="text-primary"><?=($feed->user_id==$user->id)?'Você ':$feed->user->how_to_be_called?></span>
                        compartilhou <?=Yii::t('app', '{gender,select,female{uma} male{um} other{um}}', ['gender'=>$gender]).' '?>
                        <span class="text-primary"><?=Yii::t('app', $feed->userSharing->type->name)?></span>
                    </label>
                    <div class='row'>
                        <div class="text-muted strong-6 col-xs-6 col-md-6 col-lg-6 pull-right text-right">
                            <small><?=Yii::$app->formatter->asRelativeTime($feed->created_date);?></small>
                        </div>
                    </div>
                </div>
           </div>  
        </div>
            <?php // Html::hiddenInput((new app\models\UserConversations())->formName()."['user_id2']",$f->id)?>
        </li>
        <?php endforeach;?>
        <li id='user-feeding-load-button' class="text-center"><span data-toggle='tooltip' title="Carregar  mais" class='glyphicon glyphicon-refresh btn btn-sm text-success tsize-4 strong-6'></span></li>
        <?=Html::hiddenInput((new UserFeedings)->formName().'[id]',  $feedings[count($feedings)-1]->id, ['id'=>'user-feeding-last-id']);?>
    </ul>
</div>
<?php else:?>
    <div id='user-feeding-placeholder' class="jumbotron"> 
            <h2 class="text-muted"><cite>Atualizações de amigos.</cite></h2>
    </div>
<?php endif;?>