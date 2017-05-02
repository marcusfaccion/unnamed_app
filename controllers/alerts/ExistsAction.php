<?php
namespace app\controllers\alerts;

use Yii;
use yii\base\Action;
use app\models\UserAlertNonexistence;

class ExistsAction extends Action
{
    protected $_isAjax = false;
    
    public function run()        
    {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        
        $this->isAjax = \Yii::$app->request->isAjax;
        
        $alert_existence = UserAlertNonexistence::findOne(['user_id'=>Yii::$app->user->identity->id,'alert_id'=>Yii::$app->request->post('Alerts')['id']]);
        
        // Usuário informa a existência do alerta após ter dito que não existia
       
        
        if($alert_existence->delete()){
            if($this->isAjax){
               return $this->controller->renderPartial('@app/views/_scalar_return',['scalar'=>1]);
               \Yii::$app->end(0);
            }
        }
        return $this->controller->renderPartial('@app/views/_scalar_return',['scalar'=>0]);
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