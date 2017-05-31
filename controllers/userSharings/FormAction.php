<?php
namespace app\controllers\userSharings;

use Yii;
use yii\base\Action;
use app\models\Users;
use app\models\UserSharingTypes;

class FormAction extends Action
{
    protected $_isAjax = false;
    
    public function run()         
    {  
        $this->isAjax = Yii::$app->request->isAjax;
        
        $user = Users::findOne(Yii::$app->user->identity->id);
        $sharing_type = UserSharingTypes::findOne(Yii::$app->request->get('UserSharings')['sharing_type_id']);
        
        if($this->isAjax){
            return $this->controller->renderAjax('_form',['sharing_type'=>$sharing_type, 'user'=>$user]);
            \Yii::$app->end(0);
        }
        return $this->controller->renderPartial('_form',['sharing_type'=>$sharing_type, 'user'=>$user]);
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