<?php
namespace app\controllers\bikeKeepers;

use Yii;
use yii\base\Action;
use app\models\BikeKeepers;
use app\models\Users;
use marcusfaccion\helpers\String;

class CreateAction extends Action
{
    protected $_isAjax = false;
    
    public function run()        
    {
        
        $bike_keeper = new BikeKeepers(['scenario'=>BikeKeepers::SCENARIO_CREATE]);
        $this->isAjax = \Yii::$app->request->isAjax;
        
        $bike_keeper->attributes = Yii::$app->request->post('BikeKeepers');
        $bike_keeper->created_date = date('Y-m-d H:i:s');
        $bike_keeper_user = Users::findOne($bike_keeper->user_id);
        
        if($bike_keeper->upload()){
           $bike_keeper->save(); 
            Yii::$app->session->setFlash('successfully-saved-bikeKeepers', 'Bicicletário salvo com sucesso!');
            if($this->isAjax){
               return $this->controller->renderAjax('view', ['bike_keeper' => $bike_keeper, 'bike_keeper_user'=>$bike_keeper_user, 'isAjax'=>$this->isAjax]);
               \Yii::$app->end(0);
            }
            return $this->controller->render('view', ['bike_keeper' => $bike_keeper, 'bike_keeper_user'=>$bike_keeper_user,'isAjax'=>$this->isAjax]);
        }
        
//        return ($this->isAjax)? 
//        $this->controller->renderAjax("_bike-keeper-form", ['bike_keeper'=>$bike_keeper, 'bike_keeper_user'=>$bike_keeper_user ,'isAjax'=>$this->isAjax])
//        :
//        $this->controller->render("_bike-keeper-form", ['bike_keeper'=>$bike_keeper, 'bike_keeper_user'=>$bike_keeper_user ,'isAjax'=>$this->isAjax]);
          return $this->controller->renderAjax("_bike-keeper-form", ['bike_keeper'=>$bike_keeper, 'bike_keeper_user'=>$bike_keeper_user ,'isAjax'=>$this->isAjax]);      
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