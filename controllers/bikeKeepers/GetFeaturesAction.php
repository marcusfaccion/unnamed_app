<?php
namespace app\controllers\bikeKeepers;

use Yii;
use yii\base\Action;
use app\models\BikeKeepers;

class GetFeaturesAction extends Action
{
    public function run()
    {
        $bike_keepers = BikeKeepers::find()->where(['enable'=>1])->orderBy(['id'=>'desc'])->all();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->controller->renderAjax('json', ['bike_keepers'=>$bike_keepers]);
    }
}