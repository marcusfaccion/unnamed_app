<?php
namespace app\controllers\app;

use Yii;
use yii\base\Action;

class GetConfirmMessageAction extends Action
{
    public function run()
    {
        return $this->controller->renderPartial('_get_confirm_message', ['text'=>Yii::t('app', Yii::$app->request->post('confirm_message')) ]);
    }
}