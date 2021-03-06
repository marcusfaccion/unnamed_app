<?php
namespace app\controllers\bikeKeepers;

use Yii;
use yii\base\Action;
use app\models\BikeKeepers;
use yii\web\UploadedFile;
use app\models\BikeKeepersMultimedias;

class UpdateAction extends Action
{
    protected $_isAjax = false;
    
    public function run()        
    {
        
        $this->isAjax = \Yii::$app->request->isAjax;

        
        if(Yii::$app->request->post('BikeKeepers')['id']){

            $bike_keeper = BikeKeepers::findOne(Yii::$app->request->post('BikeKeepers')['id']);

            if(Yii::$app->request->post('nonSubmition')){
                if($this->isAjax){
                     return $this->controller->renderAjax("_bike_keeper_form", ['bike_keeper'=>$bike_keeper, 'isAjax'=>$this->isAjax]);
                }
            }

            $bike_keeper->scenario = BikeKeepers::SCENARIO_UPDATE;
            $bike_keeper->attributes = Yii::$app->request->post('BikeKeepers');
            $bike_keeper->cost = $bike_keeper->public?null:$bike_keeper->cost; //se for bicicletário publico o custo é 0
            $bike_keeper->used_capacity = $bike_keeper->public?null:$bike_keeper->used_capacity; //se for bicicletário publico não é possível monitorar a quantidade de vagas utilizadas
            $bike_keeper->email = $bike_keeper->public?null:$bike_keeper->email; //se for bicicletário não aceitar formas de contato com o gerente
            $bike_keeper->tel = $bike_keeper->public?null:$bike_keeper->tel; //se for bicicletário não aceitar formas de contato com o gerente
            $bike_keeper->is_open = $bike_keeper->public?1:$bike_keeper->is_open;
            $bike_keeper->multimedia_files = UploadedFile::getInstances($bike_keeper, 'multimedia_files');
            
            if($bike_keeper->used_capacity>0 && Yii::$app->request->post('BikeKeepers')['capacity']>0){
                if((Yii::$app->request->post('BikeKeepers')['capacity']-$bike_keeper->used_capacity)>=0){
                    $bike_keeper->capacity = Yii::$app->request->post('BikeKeepers')['capacity'];
                }else{
                    $bike_keeper->addError('capacity', 'Capacidade menor que a ocupada no momento que é '.$bike_keeper->used_capacity);
                }
            }
            
            $bike_keeper->updated_date = date('Y-m-d H:i:s');
            if(!$bike_keeper->hasErrors() && $bike_keeper->save() && $bike_keeper->upload()){
                if(count($bike_keeper->_multimedias)>0){
                    BikeKeepersMultimedias::deleteAll(['bike_keepers_id'=>$bike_keeper->id]); // excluir fisicamente
                    foreach ($bike_keeper->_multimedias as $multimedia){
                        $bm = new BikeKeepersMultimedias(['scenario'=>BikeKeepersMultimedias::SCENARIO_CREATE]);
                        $bm->bike_keepers_id = $bike_keeper->id;
                        $bm->multimedias_id = $multimedia->id;
                        $bm->save();
                    }
                }
                Yii::$app->session->setFlash('successfully-saved-bike-keepers', 'O bicicletário foi atualizado e ajudará outras pessoas a pedalar com segurança!');
                if($this->isAjax){
                   // $this->controller->renderAjax('@bike-keepers/views/item/view', ['bike_keeper' => $bike_keeper, 'isAjax'=>$this->isAjax]);
                   return $this->controller->renderAjax('view', ['bike_keeper' => $bike_keeper, 'isAjax'=>$this->isAjax]);
                   \Yii::$app->end(0);
                }
                return $this->controller->render('view', ['bike_keeper' => $bike_keeper, 'isAjax'=>$this->isAjax]);
            }
        }
        
        if($this->isAjax){
            return $this->controller->renderAjax("_bike_keeper_form", ['bike_keeper'=>$bike_keeper, 'isAjax'=>$this->isAjax]);
        }
        
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