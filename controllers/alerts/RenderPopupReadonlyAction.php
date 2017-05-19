<?php
namespace app\controllers\alerts;

use yii\base\Action;
use app\models\Alerts;

class RenderPopupReadonlyAction extends Action
{
    public function run($id)        
    {
        $alert = Alerts::findOne($id);
        return $this->controller->renderAjax('_popup_readonly',  ['alert'=>$alert]);
    }
}