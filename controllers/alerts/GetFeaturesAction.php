<?php
namespace app\controllers\alerts;

use Yii;
use yii\base\Action;
use app\models\Alerts;

class GetFeaturesAction extends Action
{
    public function run()
    {
        $alerts = Alerts::find(['enable'=>1])->orderBy(['id'=>'desc'])->all();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->controller->renderAjax('json', ['alerts'=>$alerts]);
    }
}