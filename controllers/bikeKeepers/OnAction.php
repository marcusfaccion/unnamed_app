<?php
namespace app\controllers\bikeKeepers;

use Yii;
use yii\base\Action;
use app\models\BikeKeepers;

class OnAction extends Action
{
    protected $_isAjax = false;
    
    public function run()        
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        
        $this->isAjax = Yii::$app->request->isAjax;
        $bike_keeper = Bikekeepers::findOne(Yii::$app->request->post('BikeKeepers')['id']);
        $bike_keeper->scenario = BikeKeepers::SCENARIO_UPDATE;
        
        
        if($bike_keeper && $bike_keeper->goUp()){
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