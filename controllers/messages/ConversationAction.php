<?php
namespace app\controllers\messages;

use Yii;
use yii\base\Action;
use app\models\UserConversations;
use app\models\Users;
use app\models\UserConversationAlerts;

class ConversationAction extends Action
{
    public function run($user_id, $user_id2)
    {
        $user = Users::findOne($user_id);
        $user2 = Users::findOne($user_id2);
        
        //Apagando os alertas para essa conversa
        UserConversationAlerts::deleteAll(['user_id'=>$user_id, 'user_id2'=>$user_id2]);
        
        $conversations = UserConversations::find()->where(['or',['user_id'=>$user_id, 'user_id2'=>$user_id2], ['user_id'=>$user_id2, 'user_id2'=>$user_id]])->orderBy('created_date')->all();
        return $this->controller->renderPartial('_conversation',['conversations'=>$conversations, 'user'=>$user, 'user2'=>$user2]);
    }
}