<?php
/* @var $this yii\web\View */
/* @var $user app\models\User */
?>
<div>
    <ul class="list-inline list-unstyled">
        <?php foreach($user->friendshipRequests as $friendshipRequest):?>
        <li>
            <div class="col-lg-5">
                <div class="col-lg-10">
                Nome: <?=$friendshipRequest->requester->first_name; ?>    
                </div>
                <div class="col-lg-10">
                Login: <?=$friendshipRequest->requester->username; ?>    
                </div>
                <div class="col-lg-10">
                Data: <?=$friendshipRequest->created_date; ?>    
                </div>
            </div>
            
        </li>
        <?php endforeach;?>
    </ul>
</div>
