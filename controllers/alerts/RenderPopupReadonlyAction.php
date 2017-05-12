<?php
namespace app\controllers\alerts;

use Yii;
use yii\base\Action;
use app\models\Alerts;
use app\models\UserAlertRates;
use app\models\UserAlertNonexistence;

class RenderPopupReadonlyAction extends Action
{
    public function run($id)        
    {
        $alert = Alerts::findOne($id);
        return $this->controller->renderAjax('_popup_readonly',  ['alert'=>$alert]);
    }
}