<?php
namespace app\controllers\bikeKeepers;

use Yii;
use yii\base\Action;
use app\models\BikeKeepers;

class GetFeatureAction extends Action
{
    public function run($id)
    {
        $bike_keepers = BikeKeepers::find()->where(['id'=>$id])->all();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->controller->renderAjax('json', ['bike_keepers'=>$bike_keepers]);
    }
}