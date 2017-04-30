<?php
namespace app\controllers\alerts;

use Yii;
use yii\base\Action;
use app\models\Alerts;
use app\models\UserAlertRates;
use app\models\UserAlertExistence;

class RenderPopupAction extends Action
{
    public function run($id)        
    {
        $alert = Alerts::findOne($id);
        $user_rating = UserAlertRates::findOne(['user_id'=>Yii::$app->user->identity->id,'alert_id'=>$id]);
        $user_alert_existence = UserAlertExistence::findOne(['user_id'=>Yii::$app->user->identity->id,'alert_id'=>$id]);
        return $this->controller->renderAjax('_popup',  ['alert'=>$alert, 'user_avaliation'=>$user_rating, 'user_alert_existence'=>$user_alert_existence]);
    }
}