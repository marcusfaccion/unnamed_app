<?php
namespace app\controllers\alerts;

use Yii;
use yii\base\Action;
use app\models\Alerts;

class GetFeatureAction extends Action
{
    public function run($id)
    {
        $alerts = Alerts::find()->where(['id'=>$id])->all();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->controller->renderAjax('json', ['alerts'=>$alerts]);
    }
}