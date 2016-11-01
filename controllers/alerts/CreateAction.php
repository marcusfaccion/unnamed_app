<?php
namespace app\controllers\alerts;

use Yii;
use yii\base\Action;
use app\models\AlertTypes;
use app\models\Alerts;
use app\models\Users;
use marcusfaccion\helpers\String;

class CreateAction extends Action
{
    protected $_isAjax = false;
    
    public function run()        
    {
        
        $alert = new Alerts(['scenario'=>Alerts::SCENARIO_CREATE]);
        $this->isAjax = \Yii::$app->request->post('isAjax');
        
        $alert->attributes = Yii::$app->request->post('Alerts');
        $alert->created_date = date('Y-m-d H:i:s');
        $alert->visible = 1;
       
        $alert_type = AlertTypes::findOne($alert->type_id);
        $alert_user = Users::findOne($alert->user_id);
        
        $alert_type_name = explode('_', strtolower(str_replace(' ', '_', $alert_type->description)));
        $alert_type_name = count($alert_type_name)>1 ? implode('_', $alert_type_name) : $alert_type_name[0];
        
        if($alert->save()){
            
            Yii::$app->session->setFlash('successfully-saved-alerts', 'Alerta salvo com sucesso');
            if($this->isAjax){
               // $this->controller->renderAjax('@alerts/views/item/view', ['alert' => $alert, 'isAjax'=>$this->isAjax]);
               return $this->controller->renderAjax('view', ['alert' => $alert, 'alert_type'=>$alert_type, 'alert_user'=>$alert_user, 'isAjax'=>$this->isAjax]);
               \Yii::$app->end(0);
            }
            return $this->controller->render('view', ['alert' => $alert, 'alert_type'=>$alert_type, 'alert_user'=>$alert_user,'isAjax'=>$this->isAjax]);
        }
        
        if($this->isAjax){
             return $alert_type ? $this->controller->renderAjax("_alert-wform-type-". String::changeChars($alert_type_name, String::PTBR_DIACR_SEARCH, String::PTBR_DIACR_REPLACE),
            ['alert'=>$alert, 'alert_type'=>$alert_type, 'alert_user'=>$alert_user , 'isAjax'=>$this->isAjax]):"ERRO 400";
        }
            
        
        
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