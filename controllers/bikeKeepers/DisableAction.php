<?php
namespace app\controllers\bikeKeepers;

use Yii;
use yii\base\Action;
use app\models\BikeKeepers;
use app\models\UserBikeKeeperNonexistence; 

class DisableAction extends Action
{
    protected $_isAjax = false;
    
    public function run()        
    {
        
        $this->isAjax = \Yii::$app->request->isAjax;
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $bike_keepers = BikeKeepers::findAll(Yii::$app->request->post('BikeKeepers')['id']);
        
        if(count($bike_keepers)>1){
           // Excluindo os reportes de não existência associados
            foreach ($bike_keepers as $bike_keeper){
               UserBikeKeeperNonexistence::deleteAll(['bike_keeper_id'=>$bike_keeper->id]);
            }   
          if(BikeKeepers::disableAll($bike_keepers)){
                Yii::$app->session->setFlash('successfully-disabled-bike-keepers', 'Bicicletários desativados com sucesso');
                if($this->isAjax){
                  return $this->controller->renderPartial('@app/views/_scalar_return',['scalar'=>1]);
                   \Yii::$app->end(0);
                }    
          }  
        }else
        if(count($bike_keepers)==1){
            // Excluindo os reportes de não existência associados
            UserBikeKeeperNonexistence::deleteAll(['bike_keeper_id'=>$bike_keeper[0]->id]);
            if($bike_keepers[0]->disable()){
                Yii::$app->session->setFlash('successfully-disabled-bike-keepers', 'Bicicletário desativado com sucesso');
                if($this->isAjax){
                  return $this->controller->renderPartial('@app/views/_scalar_return',['scalar'=>1]);
                   \Yii::$app->end(0);
                }     
           }
        }else{
            if($this->isAjax){
              return $this->controller->renderPartial('@app/views/_scalar_return',['scalar'=>0]);
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