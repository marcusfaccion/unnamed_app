<?php
namespace app\modules\alerts\controllers\widget;

use Yii;
use yii\base\Action;
use app\modules\alerts\models\AlertTypes;
use app\modules\alerts\models\Alerts;
use app\modules\user\models\Users;
use marcusfaccion\helpers\String;

class FormAction extends Action
{
    //public function run($type_id)
    public function run()        
    {
        
        $alert = new Alerts();
        $alert_type = AlertTypes::findOne(['id'=>$_POST['type_id']]);
   
        $alert->type_id = $alert_type->id;
        $alert->user_id = Yii::$app->user->id;
        
        $alert_type_name = explode('_', strtolower(str_replace(' ', '_', $alert_type->description)));
        $alert_type_name = count($alert_type_name)>1 ? implode('_', $alert_type_name) : $alert_type_name[0];
        
        return $alert_type ? $this->controller->renderAjax("_alert-wform-type-". String::changeChars($alert_type_name, String::PTBR_DIACR_SEARCH, String::PTBR_DIACR_REPLACE).".part.php",
                ['alert' => $alert, 'alert_type' => $alert_type, 'alert_user' => Users::findOne($alert->user_id)]):"ERRO 400";
    }
}