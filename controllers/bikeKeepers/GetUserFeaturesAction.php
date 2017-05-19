<?php
namespace app\controllers\bikeKeepers;

use Yii;
use yii\base\Action;
use app\models\BikeKeepers;

class GetUserFeaturesAction extends Action
{
    public function run($user_id)
    {
        $bike_keepers = BikeKeepers::find()->where(['enable'=>1, 'user_id'=>$user_id])->orderBy(['id'=>'desc'])->all();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->controller->renderAjax('json', ['bike_keepers'=>$bike_keepers]);
    }
}