<?php
namespace app\controllers\alerts;

use yii\base\Action;
use app\models\Alerts;

class RenderPopupAction extends Action
{
    public function run($id)        
    {
        $alert = Alerts::findOne($id);
        return 'Loren Ipsum generator '.var_dump($alert->geojson_array);
    }
}