<?php
namespace app\controllers\alerts;

use Yii;
use yii\base\Action;
use app\models\Alerts;
use app\models\UserAlertRates;

class IlikeAction extends Action
{
    protected $_isAjax = false;
    
    public function run()        
    {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        
        $this->isAjax = \Yii::$app->request->isAjax;
        
        $alert = Alerts::findOne(Yii::$app->request->post('Alerts')['id']);
        $alert->likes = Yii::$app->request->post('Alerts')['likes'];
        
        if(Yii::$app->request->post('Alerts')['avaliated']){
            // Carrega a aviliação do usuário caso exista
            $user_rating = UserAlertRates::findOne(['user_id'=>Yii::$app->user->identity->id,'alert_id'=>$alert->id]);
            
            if($user_rating){
                $user_rating->rating = 'likes';
                $user_rating->updated_date = date('Y-m-d H:i:s');
            }else{
                // Usuário não avaliou ainda esse alerta
                $user_rating = new UserAlertRates();
                $user_rating->user_id = Yii::$app->user->identity->id;
                $user_rating->alert_id = $alert->id;
                $user_rating->created_date = date('Y-m-d H:i:s');
                $user_rating->rating = 'likes';
                $user_rating->save(false);
            }
        }else{
            // excluir a avaliação do usuário
            UserAlertRates::findOne(['user_id'=>Yii::$app->user->identity->id,'alert_id'=>$alert->id])->delete();
        }
        
         if($alert->save(false)){
            
            if($this->isAjax){
               return $this->controller->renderPartial('@app/views/_scalar_return',['scalar'=>$alert->likes]);
               \Yii::$app->end(0);
            }
        }
        return $this->controller->renderPartial('@app/views/_scalar_return',['scalar'=>null]);
        \Yii::$app->end(0);
    }
    
    /*public function afterRun() {
        parent::afterRun();
        if($this->isAjax){
        
        }
    }*/
    
    /**
     * Getter
     * @return boolean true se a requisição é via ajax false contrário
     */
    public function getisAjax(){
        return $this->_isAjax;
    }
    /**
     * Setter
     * @param boolean $value 
     */
    public function setisAjax($value){
        $this->_isAjax = $value;
    }
}