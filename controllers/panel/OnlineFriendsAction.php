<?php
namespace app\controllers\panel;

use Yii;
use yii\base\Action;
use app\models\UserConversations;
use app\models\Users;

class OnlineFriendsAction extends Action
{
    public function run($user_id, $user_id2)
    {
        $user = Users::findOne($user_id);
        $user_id2 = !empty($user_id2)?Users::findOne($user_id2)->id:-1;
        return $this->controller->renderPartial('_friends_sidebar',['user'=>$user, 'user_id2'=>$user_id2,]);
    }
}