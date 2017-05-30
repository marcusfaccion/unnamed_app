<?php
namespace app\controllers\users;

use Yii;
use yii\base\Action;
use app\models\OnlineUsers;

class SetOnlineAction extends Action
{
    protected $_isAjax = false;
    
    public function run()        
    {  
        $this->isAjax = Yii::$app->request->isAjax;
        $online = OnlineUsers::findOne(Yii::$app->request->post('OnlineUsers')['user_id']);
        
        $online->updated_date = date('Y-m-d H:i:s');
        $online->save();
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        
        if($this->isAjax){
            return $this->controller->renderPartial('@app/views/_scalar_return',['scalar'=>1]);
            \Yii::$app->end(0);
        }
        return $this->controller->renderPartial('@app/views/_scalar_return',['scalar'=>1]);
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