<?php
namespace app\controllers\bikeKeepers;

use yii\base\Action;
use app\models\BikeKeepers;

class RenderPopupAction extends Action
{
    public function run($id)        
    {
        $bike_keeper = BikeKeepers::findOne($id);
        return 'Loren Ipsum generator '.var_dump($bike_keeper->geojson_array);
    }
}