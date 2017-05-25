<?php
namespace app\controllers\bikeKeepers;

use Yii;
use yii\base\Action;
use app\models\BikeKeepers;
use app\models\UserBikeKeeperNonexistence;

class DeleteUserBikeKeeperNonexistenceAction extends Action
{
    protected $_isAjax = false;
    
    public function run()        
    {
        
        $this->isAjax = \Yii::$app->request->isAjax;
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        
        $bike_keeper = BikeKeepers::findOne(Yii::$app->request->post('BikeKeepers')['id']);
          
          $ret = true; // controle de exclusão de cada item
          if($bike_keeper){
              foreach ($bike_keeper->nonExistence as $bne){
                  $ret = $ret && $bne->delete();
              }  
              if($this->isAjax && $ret){
                return $this->controller->renderPartial('@app/views/_scalar_return',['scalar'=>1]);
                \Yii::$app->end(0);
              }    
          }
        return $this->controller->renderPartial('@app/views/_scalar_return',['scalar'=>0]);  
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