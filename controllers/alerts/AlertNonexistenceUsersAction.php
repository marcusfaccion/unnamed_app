<?php
namespace app\controllers\alerts;

use Yii;
use yii\base\Action;
use app\models\Alerts;
use app\models\UserAlertNonexistence;

class AlertNonexistenceUsersAction extends Action
{
    public function run()
    {
        $alert = Alerts::findOne(Yii::$app->request->get('Alerts')['id']);
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        if($alert){
           // $alert->nonExistence = UserAlertNonexistence::find()->distinct()->where(['alert_id'=>$alert->id])->orderBy(['created_date'=>'desc'])->all();
            Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
            return $this->controller->renderPartial('_active_nonalerts_users',['alert'=>$alert]);
        }
        return $this->controller->renderPartial('@app/views/_scalar_return',['scalar'=>0]);
    }
}