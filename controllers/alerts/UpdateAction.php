<?php
namespace app\controllers\alerts;

use Yii;
use yii\base\Action;
use app\models\AlertTypes;
use app\models\Alerts;
use app\models\Users;
use marcusfaccion\helpers\String;

class UpdateAction extends Action
{
    protected $_isAjax = false;
    
    public function run()        
    {
        
        $alert = Alerts::findOne(Yii::$app->request->post('Alerts')['id']);
        $this->isAjax = \Yii::$app->request->isAjax;
        
        $alert_type_name = explode('_', strtolower(str_replace(' ', '_', $alert->type->description)));
        $alert_type_name = count($alert_type_name)>1 ? implode('_', $alert_type_name) : $alert_type_name[0];
        
        if(Yii::$app->request->post('nonSubmition')){
            if($this->isAjax){
                return $alert->type ? $this->controller->renderAjax("_alert-form-type-". String::changeChars($alert_type_name, String::PTBR_DIACR_SEARCH, String::PTBR_DIACR_REPLACE),
                        ['alert' => $alert, 'alert_type' => $alert->type, 'alert_user' => $alert->user]):"ERRO 400";
            }
        }
        
        $alert->attributes = Yii::$app->request->post('Alerts');
        $alert->updated_date = date('Y-m-d H:i:s');
        
        if($alert->save()){
            
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