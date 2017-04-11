<?php
namespace app\controllers\home;

use Yii;
use yii\base\Action;

class GetConfirmMessageAction extends Action
{
    public function run()
    {
        return $this->controller->renderAjax('get-confirm-message', ['message'=>Yii::$app->request->post('message')]);
    }
}