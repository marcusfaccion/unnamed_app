<?php
namespace app\controllers\bikeKeepers;

use Yii;
use yii\base\Action;
use app\models\BikeKeepers;

class MultimideasAction extends Action
{
    public function run($id)
    {
        $bike_keeper = BikeKeepers::findOne($id);
        Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
        return $this->controller->renderAjax('_multimedias', ['bike_keeper'=>$bike_keeper]);
    }
}