<?php
namespace app\controllers\alerts;

use Yii;
use yii\base\Action;
use app\models\Alerts;

class DisableAction extends Action
{
    protected $_isAjax = false;
    
    public function run()        
    {
        
        $this->isAjax = \Yii::$app->request->isAjax;
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        
        if(Alerts::findOne(Yii::$app->request->post('Alerts')['id'])->disable()){

            Yii::$app->session->setFlash('successfully-disabled-alerts', 'Alerta desativado com sucesso');
            if($this->isAjax){
              return $this->controller->renderPartial('@app/views/_scalar_return',['scalar'=>1]);
               \Yii::$app->end(0);
            }    
        }else{
            if($this->isAjax){
              return $this->controller->renderPartial('@app/views/_scalar_return',['scalar'=>0]);
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