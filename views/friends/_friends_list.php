<?php
/* @var $this yii\web\View */
/* @var $user app\models\Users */

?>
<div>
    <ul class="list-inline list-unstyled">
        <?php foreach($user->friends as $friend):?>
        <li>
            <div class="col-lg-3">
                <div class="col-lg-2">
                Nome: <?=$friend->first_name; ?>    
                </div>
                <div class="col-lg-2">
                Login: <?=$friend->username; ?>    
                </div>
            </div>
            
        </li>
        <?php endforeach;?>
    </ul>
</div>
