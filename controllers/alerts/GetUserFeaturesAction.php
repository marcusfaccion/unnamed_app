<?php
namespace app\controllers\alerts;

use Yii;
use yii\base\Action;
use app\models\Alerts;

class GetUserFeaturesAction extends Action
{
    public function run($user_id)
    {
        $alerts = Alerts::find()->where(['enable'=>1, 'user_id'=>$user_id])->orderBy(['id'=>'desc'])->all();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->controller->renderAjax('json', ['alerts'=>$alerts]);
    }
}