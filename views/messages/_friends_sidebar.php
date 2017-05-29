<?php
use yii\helpers\Html;
?>
<ul class="list-unstyled hover">
<?php foreach($user->friends as $f): ?>
<li class="messages-friend border-radius-8">
<div class="top-buffer-2">
    <div class="row">
        <div class="col-lg-9 col-md-9 col-xs-9">
            <img src='<?=$f->avatar?>' class="tall-px5-8 wide-px5-8 img-circle bg-light-default"/>
            <label class="left-buffer-1"><?=$f->how_to_be_called?></label>
        </div>
        <div class="col-lg-3 col-md-3 col-xs-3">
            <?php if($f->online):?>
            <span data-toggle="popover" data-content="Online" data-placement='top'>
                <img src="images/green_32.png" class="img-circle tall-px5-3 wide-px5-3 top-buffer-3"/>
            </span>
            <?php else:?>
            <span data-toggle="popover" data-content="Offline" data-placement='top'>
                <img src="images/gray_32.png" class="img-circle tall-px5-3 wide-px5-3 top-buffer-3"/>
            </span>
            <?php endif;?>
        </div>
   </div>  
</div>
    <?=Html::hiddenInput((new app\models\UserConversations())->formName()."['user_id2']",$f->id)?>
</li>
<?php endforeach; ?>
</ul>