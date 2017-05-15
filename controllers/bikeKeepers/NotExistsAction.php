<?php
namespace app\controllers\bikeKeepers;

use Yii;
use yii\base\Action;
use app\models\UserBikeKeeperNonexistence;

class NotExistsAction extends Action
{
    protected $_isAjax = false;
    
    public function run()        
    {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        
        $this->isAjax = \Yii::$app->request->isAjax;
        
        
        // Usuário informa a inexistência
        $bike_keeper_existence = new UserBikeKeeperNonexistence;
        $bike_keeper_existence->user_id = Yii::$app->user->identity->id;
        $bike_keeper_existence->bike_keeper_id = Yii::$app->request->post('BikeKeepers')['id'];
        $bike_keeper_existence->created_date = date('Y-m-d H:i:s');
        
        if($bike_keeper_existence->save()){
            if($this->isAjax){
               return $this->controller->renderPartial('@app/views/_scalar_return',['scalar'=>1]);
               \Yii::$app->end(0);
            }
        }
        return $this->controller->renderPartial('@app/views/_scalar_return',['scalar'=>0]);
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