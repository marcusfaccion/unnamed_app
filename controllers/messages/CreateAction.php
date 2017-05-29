<?php
namespace app\controllers\messages;

use Yii;
use yii\base\Action;
use app\models\UserConversations;
use app\models\Users;

class CreateAction extends Action
{
    public function run()
    {
        $conversation = new UserConversations(['scenario'=>UserConversations::SCENARIO_CREATE]);
        $conversation->attributes = Yii::$app->request->post('UserConversations');
        $conversation->created_date = date('Y-m-d H:i:s');
        $conversation->save();
        
        $conversations = UserConversations::find()->where(['or',['user_id'=>$conversation->user_id, 'user_id2'=>$conversation->user_id2], ['user_id'=>$conversation->user_id2, 'user_id2'=>$conversation->user_id]])->orderBy('created_date')->all();
        
        return $this->controller->renderPartial('_conversation',['conversations'=>$conversations, 'user'=>$conversation->user, 'user2'=>$conversation->user2]);
    }
}