<?php
namespace app\controllers\bikeKeepers;

use yii\base\Action;
use app\models\BikeKeepers;

class RenderPopupReadonlyAction extends Action
{
    public function run($id)        
    {
        $bike_keeper = BikeKeepers::findOne($id);
        return $this->controller->renderAjax('_popup_readonly',  ['bike_keeper'=>$bike_keeper]);
    }
}