<?php
namespace app\modules\alerts\controllers\item;

use Yii;
use yii\base\Action;
use app\modules\alerts\models\AlertTypes;
use app\modules\alerts\models\Alerts;
use app\modules\alerts\models\Geometries;
use marcusfaccion\helpers\String;

class RenderPopupAction extends Action
{
    //public function run($type_id)
    public function run()        
    {
        return $this->controller->renderContent('A ação funcionou!');
        
        $alert = new Alerts();
        
        $alert->attributes = Yii::$app->request->post('Alerts');
        $alert->geom = Yii::$app->request->post('Alerts')['geom'];
        $alert->created_date = date('Y-m-d H:i:s');
        
        $geometry = new Geometries();
        $geometry->geom = $alert->geom;
        
        //map projection ESPG4326:WGS84
        //map projection EPSG3857 online maps - Spherical Mercator projection
        $geometry->srid = 3857;
        //$geometry->name 
                
        Yii::$app->end(0);
        
        $alert_type = AlertTypes::findOne(['id'=>$alert->type_id]);
        $alert_type_name = explode('_', strtolower(str_replace(' ', '_', $alert_type->description)));
        $alert_type_name = count($alert_type_name)>1 ? implode('_', $alert_type_name) : $alert_type_name[0];
        
        if($alert->save()){
            if($ajax){
                $this->controller->renderAjax('create', ['alert' => $alert]);
                \Yii::$app->end(0);
            }
            $this->controller->render('create', ['alert' => $alert]);
        }else{
            return $alert_type ? $this->controller->renderAjax("@alerts/views/widget/_alert-wform-type-". String::changeChars($alert_type_name, String::PTBR_DIACR_SEARCH, String::PTBR_DIACR_REPLACE).".part.php",
                ['alert' => $alert]):"ERRO 400";
        }
        
    }
}