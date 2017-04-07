<?php 

/**
 * @var Friends $friends
 */

use marcusfaccion\helpers\String;
use app\assets\AppFriendsAsset;
use app\models\Users;
use app\models\UserFriendshipRequests;
?>
    <div class="friends-widget-index">
          <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active"><a href="#my-friends" aria-controls="my-friends" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-user"></span> <span class="hidden-xs">Amigos</span> <?php if(count($user->friends)>0): ?><span class="badge bg-primary"><small><?=count($user->friends)?></small><span/><?php else: ?><?php endif;?></a></li>
              <li role="presentation"><a href="#requests" aria-controls="requests" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-user"></span><small><span class="glyphicon glyphicon-bell"></span></small>  <span class="hidden-xs">Solicitações</span> <?php if(count($user->friendshipRequests)>0):?><span class="badge bg-primary"><small><?=count($user->friendshipRequests)?></small><span/><?php else:?><?php endif;?></a></li>
              <li role="presentation"><a href="#search" aria-controls="search" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-user"></span><small><span class="glyphicon glyphicon-plus"></span></small>  <span class="hidden-xs">Pesquisar</span></a></li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="my-friends">
                 <?php echo $this->renderAjax('_friends_list', ['user'=>$user]); ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="requests">
                <?php echo $this->renderAjax('_friends_requests', ['user'=>$user]); ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="search">
                <?php echo $this->renderAjax('_friends_search_form'); ?>
            </div>
          </div>
       <?php /* 
       <div id="home_actions_friends_form" class="row col-xs-offset-1 bottom-buffer-5">
          <?php echo $this->renderAjax('_friends-form', ['friends'=>$friends]); ?>
       </div>*/?>
    </div>