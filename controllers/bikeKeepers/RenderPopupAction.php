<?php
namespace app\controllers\bikeKeepers;

use Yii;
use yii\base\Action;
use app\models\BikeKeepers;
use app\models\UserBikeKeeperRates;
use app\models\UserBikeKeeperNonexistence;

class RenderPopupAction extends Action
{
    public function run($id)        
    {
        $bike_keeper = BikeKeepers::findOne($id);
        $user_rating = UserBikeKeeperRates::findOne(['user_id'=>Yii::$app->user->identity->id,'bike_keeper_id'=>$id]);
        $user_bike_keeper_existence = UserBikeKeeperNonexistence::findOne(['user_id'=>Yii::$app->user->identity->id,'bike_keeper_id'=>$id]);
        return $this->controller->renderAjax('_popup',  ['bike_keeper'=>$bike_keeper, 'user_avaliation'=>$user_rating, 'user_bike_keeper_existence'=>$user_bike_keeper_existence]);
    }
}