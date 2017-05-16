<?php
namespace app\controllers\alerts;

use Yii;
use yii\base\Action;
use app\models\Alerts;
use app\models\UserAlertNonexistence;

class DeleteUserAlertNonexistenceAction extends Action
{
    protected $_isAjax = false;
    
    public function run()        
    {
        
        $this->isAjax = \Yii::$app->request->isAjax;
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        
        $alert = Alerts::findOne(Yii::$app->request->post('Alerts')['id']);
          
          $ret = true; // controle de exclusão de cada item
          if($alert){
              foreach ($alert->nonExistence as $ane){
                  $ret = $ret && $ane->delete();
              }  
              if($this->isAjax && $ret){
                return $this->controller->renderPartial('@app/views/_scalar_return',['scalar'=>1]);
                \Yii::$app->end(0);
              }    
          }
        return $this->controller->renderPartial('@app/views/_scalar_return',['scalar'=>0]);  
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