<?php
namespace app\controllers\alerts;

use Yii;
use yii\base\Action;
use app\models\AlertTypes;
use app\models\Alerts;
use app\models\Users;
use app\models\UserSharings;
use app\models\UserSharingTypes;
use app\models\UserFeedings;
use marcusfaccion\helpers\String;

class CreateAction extends Action
{
    protected $_isAjax = false;
    
    public function run()        
    {
        
        $this->isAjax = \Yii::$app->request->isAjax;
        $alert = new Alerts(['scenario'=>Alerts::SCENARIO_CREATE]);
        $user_sharing = new UserSharings(['scenario'=>  UserSharings::SCENARIO_CREATE]);
        $user_feeding = new UserFeedings(['scenario'=> UserFeedings::SCENARIO_CREATE]);
        
        $alert->attributes = Yii::$app->request->post('Alerts');
        $alert->created_date = date('Y-m-d H:i:s');
        

        
        //Momentaneamente alterando o timezone da aplicação para salvar data futura de duração do alerta de America/Sao_Paulo para UTC no banco
        date_default_timezone_set(Yii::$app->formatter->timeZone);
        $alert->duration_date = !empty($alert->duration_date) ? gmdate('Y-m-d H:i:s', strtotime($alert->duration_date)):null;
        date_default_timezone_set(Yii::$app->formatter->defaultTimeZone);
        
        $alert_type = AlertTypes::findOne($alert->type_id);
        $alert_user = Users::findOne($alert->user_id);
        
        $alert_type_name = explode('_', strtolower(str_replace(' ', '_', $alert_type->description)));
        $alert_type_name = count($alert_type_name)>1 ? implode('_', $alert_type_name) : $alert_type_name[0];
        
        if($alert->save()){
            
            //Criando o registro no feed
            $user_sharing->user_id = $alert->user_id;
            $user_sharing->sharing_type_id = UserSharingTypes::findOne(['name'=>'alert'])->id;
            $user_sharing->content_id = $alert->id;
            $user_sharing->created_date = date('Y-m-d H:i:s');
            
            if($user_sharing->save()){
                $user_feeding->user_sharing_id = $user_sharing->id;
                $user_feeding->user_id = $user_sharing->user_id;
                $user_feeding->created_date = date('Y-m-d H:i:s');
                if($user_feeding->save()){
                    $user_sharing->user_feeding_id = $user_feeding->id;
                    $user_sharing->save(false);
                }
                
            }
            
            Yii::$app->session->setFlash('successfully-saved-alerts', 'Seu alerta foi publicado e ajudará outras pessoas a pedalar com segurança!');
            if($this->isAjax){
               // $this->controller->renderAjax('@alerts/views/item/view', ['alert' => $alert, 'isAjax'=>$this->isAjax]);
               return $this->controller->renderAjax('view', ['alert' => $alert, 'alert_type'=>$alert_type, 'alert_user'=>$alert_user, 'isAjax'=>$this->isAjax]);
               \Yii::$app->end(0);
            }
            return $this->controller->render('view', ['alert' => $alert, 'alert_type'=>$alert_type, 'alert_user'=>$alert_user,'isAjax'=>$this->isAjax]);
        }
        
        if($this->isAjax){
             return $alert_type ? $this->controller->renderAjax("_alert-form-type-". String::changeChars($alert_type_name, String::PTBR_DIACR_SEARCH, String::PTBR_DIACR_REPLACE),
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