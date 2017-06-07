<?php
namespace app\controllers\routes;

use Yii;
use yii\base\Action;
use app\models\UserNavigationRoutes;

class GetFeatureAction extends Action
{
    public function run($id)
    {
        $routes = UserNavigationRoutes::find()->where(['id'=>$id])->all();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->controller->renderAjax('json', ['routes'=>$routes]);
    }
}