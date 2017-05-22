<?php
namespace app\controllers\bikeKeepers;

use Yii;
use yii\base\Action;
use app\models\BikeKeepers;

class UsedCapacityAction extends Action
{
    protected $_isAjax = false;
    
    public function run()        
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
        
        $bike_keeper = BikeKeepers::findOne(Yii::$app->request->post('BikeKeepers')['id']);
        $bike_keeper->scenario = BikeKeepers::SCENARIO_UPDATE;
        
        $this->isAjax = \Yii::$app->request->isAjax;
        
        if(isset(Yii::$app->request->post('BikeKeepers')['used_capacity'])){
            $bike_keeper->used_capacity = Yii::$app->request->post('BikeKeepers')['used_capacity'];
            $bike_keeper->updated_date = date('Y-m-d H:i:s');            
           if($bike_keeper->save()){ 
                Yii::$app->session->setFlash('successfully-saved-bike-keeper', 'Capacidade atualizada com sucesso!');
                if($this->isAjax){
                   return $this->controller->renderPartial('@app/views/_html_message', ['text' => 'Capacidade atualizada com sucesso!', 'css_class'=>['parent'=>'bg-success alert', 'text'=>'text-success'], 'isAjax'=>$this->isAjax]);
                   \Yii::$app->end(0);
                }
                return $this->controller->render('view', ['bike_keeper' => $bike_keeper,'isAjax'=>$this->isAjax]);
           }
        }

       return $this->controller->renderAjax("_used_capacity_form", ['bike_keeper'=>$bike_keeper, 'isAjax'=>$this->isAjax]);      
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