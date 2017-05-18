<?php
namespace app\controllers\alerts;

use Yii;
use yii\base\Action;
use app\models\Alerts;
use app\models\UserAlertNonexistence;

class DisableAction extends Action
{
    protected $_isAjax = false;
    
    public function run()        
    {
        
        $this->isAjax = \Yii::$app->request->isAjax;
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        
        $alerts = Alerts::findAll(Yii::$app->request->post('Alerts')['id']);
        
        if(count($alerts)>1){
          // Excluindo os reportes de não existência associados ao alerta
          foreach ($alerts as $alert){
              UserAlertNonexistence::deleteAll(['alert_id'=>$alert->id]);
          }  
          if(Alerts::disableAll($alerts)){
                Yii::$app->session->setFlash('successfully-disabled-alerts', 'Alertas desativados com sucesso');
                if($this->isAjax){
                  return $this->controller->renderPartial('@app/views/_scalar_return',['scalar'=>1]);
                   \Yii::$app->end(0);
                }    
          }  
        }else
        if(count($alerts)==1){
            // Excluindo os reportes de não existência associados ao alerta 
            if(UserAlertNonexistence::deleteAll(['alert_id'=>$alerts[0]->id]) && $alerts[0]->disable()){
                Yii::$app->session->setFlash('successfully-disabled-alerts', 'Alerta desativado com sucesso');
                if($this->isAjax){
                  return $this->controller->renderPartial('@app/views/_scalar_return',['scalar'=>1]);
                   \Yii::$app->end(0);
                }
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