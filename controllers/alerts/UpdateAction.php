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
        
        //Resgatar a data futura de expiração em horário local
        $alert->duration_date = !empty($alert->duration_date) ? Yii::$app->formatter->asDatetime($alert->duration_date, 'yyyy-MM-dd HH:mm:ss'):null; //icu formato
        
        if(Yii::$app->request->post('nonSubmition')){
            if($this->isAjax){
                return $alert->type ? $this->controller->renderAjax("_alert-form-type-". String::changeChars($alert_type_name, String::PTBR_DIACR_SEARCH, String::PTBR_DIACR_REPLACE),
                        ['alert' => $alert, 'alert_type' => $alert->type, 'alert_user' => $alert->user]):"ERRO 400";
            }
        }
        
        $alert->scenario = Alerts::SCENARIO_CREATE;
        $alert->attributes = Yii::$app->request->post('Alerts');
        
        //Momentaneamente alterando o timezone da aplicação para salvar data futura em UTC no banco
        date_default_timezone_set(Yii::$app->formatter->timeZone);
        $alert->duration_date = !empty($alert->duration_date) ? gmdate('Y-m-d H:i:s', strtotime($alert->duration_date)):null;
        date_default_timezone_set(Yii::$app->formatter->defaultTimeZone);
        
        $alert->updated_date = date('Y-m-d H:i:s');
        
        if($alert->save()){
            Yii::$app->session->setFlash('successfully-saved-alerts', 'Seu alerta foi atualizado e ajudará outras pessoas a pedalar com segurança!');
            if($this->isAjax){
               // $this->controller->renderAjax('@alerts/views/item/view', ['alert' => $alert, 'isAjax'=>$this->isAjax]);
               return $this->controller->renderAjax('view', ['alert' => $alert, 'alert_type'=>$alert->type, 'alert_user'=>$alert->user, 'isAjax'=>$this->isAjax]);
               \Yii::$app->end(0);
            }
            return $this->controller->render('view', ['alert' => $alert, 'alert_type'=>$alert->type, 'alert_user'=>$alert->user, 'isAjax'=>$this->isAjax]);
        }
        
        if($this->isAjax){
             return $alert_type ? $this->controller->renderAjax("_alert-form-type-". String::changeChars($alert_type_name, String::PTBR_DIACR_SEARCH, String::PTBR_DIACR_REPLACE),
            ['alert'=>$alert, 'alert_type'=>$alert->type, 'alert_user'=>$alert->user , 'isAjax'=>$this->isAjax]):"ERRO 400";
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