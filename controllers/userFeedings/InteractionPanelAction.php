<?php
namespace app\controllers\userFeedings;

use Yii;
use yii\base\Action;
use app\models\Users;
use app\models\UserFeedings;
use app\models\Alerts;
use app\models\BikeKeepers;
use app\models\UserNavigationRoutes;

class InteractionPanelAction extends Action
{
    protected $_isAjax = false;
    
    public function run()         
    {  
        $this->isAjax = Yii::$app->request->isAjax;
        
        $user = Users::findOne(Yii::$app->user->identity->id);
        $user_feeding  = UserFeedings::findOne(Yii::$app->request->get('UserFeedings')['id']);
        $content = null;
        
        if($user_feeding->userSharing->type->name==='alert'){
            $content = Alerts::findOne($user_feeding->userSharing->content_id);
        }
        if($user_feeding->userSharing->type->name==='bike keeper'){
            $content = BikeKeepers::findOne($user_feeding->userSharing->content_id);
        }
        if($user_feeding->userSharing->type->name==='route'){
            $content = UserNavigationRoutes::findOne($user_feeding->userSharing->content_id);
        }

        if($this->isAjax){
            return $this->controller->renderAjax('_interaction_panel',['user_feeding'=>$user_feeding, 'content'=>$content, 'user'=>$user]);
            \Yii::$app->end(0);
        }
        return $this->controller->renderPartial('_interaction_panel',['user_feeding'=>$user_feeding, 'content'=>$content, 'user'=>$user]);
        \Yii::$app->end(0);
    }
    
    /*public function afterRun() {
        parent::afterRun();
        if($this->isAjax){
        
        }
    }*/
    
    /**
     * Getter
     * @return boolean true se a requisição é via ajax false contrário
     */
    public function getisAjax(){
        return $this->_isAjax;
    }
    /**
     * Setter
     * @param boolean $value 
     */
    public function setisAjax($value){
        $this->_isAjax = $value;
    }
}