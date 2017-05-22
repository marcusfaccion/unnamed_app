<?php
namespace app\controllers\BikeKeepers;

use Yii;
use yii\base\Action;
use app\models\BikeKeepers;

class BikeKeeperNonexistenceUsersAction extends Action
{
    public function run()
    {
        $bike_keeper = BikeKeepers::findOne(Yii::$app->request->get('BikeKeepers')['id']);
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        if($bike_keeper){
           // $bike_keeper->nonExistence = UserBikeKeeperNonexistence::find()->distinct()->where(['bike_keeper_id'=>$bike_keeper->id])->orderBy(['created_date'=>'desc'])->all();
            Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
            return $this->controller->renderPartial('_active_nonbike_keepers_users',['bike_keeper'=>$bike_keeper]);
        }
        return $this->controller->renderPartial('@app/views/_scalar_return',['scalar'=>0]);
    }
}