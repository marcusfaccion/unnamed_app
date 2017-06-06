<?php
namespace app\controllers\bikeKeepers;

use Yii;
use yii\base\Action;
use yii\web\UploadedFile;
use app\models\BikeKeepers;
use app\models\BikeKeepersMultimedias;
use app\models\Users;
use app\models\UserSharings;
use app\models\UserSharingTypes;
use app\models\UserFeedings;

class CreateAction extends Action
{
    protected $_isAjax = false;
    
    public function run()        
    {
        
        $this->isAjax = \Yii::$app->request->isAjax;
        $bike_keeper = new BikeKeepers(['scenario'=>BikeKeepers::SCENARIO_CREATE]);
        $user_sharing = new UserSharings(['scenario'=>  UserSharings::SCENARIO_CREATE]);
        $user_feeding = new UserFeedings(['scenario'=> UserFeedings::SCENARIO_CREATE]);
        
        $bike_keeper->attributes = Yii::$app->request->post('BikeKeepers');
        $bike_keeper->multimedia_files = UploadedFile::getInstances($bike_keeper, 'multimedia_files');
        $bike_keeper->created_date = date('Y-m-d H:i:s');
        $bike_keeper_user = Users::findOne($bike_keeper->user_id);
        
        if($bike_keeper->upload()){
           if($bike_keeper->save()){
               //salvando a relação ManyMany
               foreach ($bike_keeper->_multimedias as $multimedia){
                    $bm = new BikeKeepersMultimedias(['scenario'=>BikeKeepersMultimedias::SCENARIO_CREATE]);
                    $bm->bike_keepers_id = $bike_keeper->id;
                    $bm->multimedias_id = $multimedia->id;
                    $bm->save();
               }
               
                //Criando o registro no feed
                $user_sharing->user_id = $bike_keeper->user_id;
                $user_sharing->sharing_type_id = UserSharingTypes::findOne(['name'=>'bike keeper'])->id;
                $user_sharing->content_id = $bike_keeper->id;
                $user_sharing->created_date = date('Y-m-d H:i:s');

                if($user_sharing->save()){
                    $user_feeding->user_sharing_id = $user_sharing->id;
                    $user_feeding->user_id = $user_sharing->user_id;
                    $user_feeding->created_date = date('Y-m-d H:i:s');
                    if($user_feeding->save()){
                        $user_sharing->user_feeding_id = $user_feeding->id;
                        $user_sharing->save(false);
                    }

                }
               
            Yii::$app->session->setFlash('successfully-saved-bike-keeper', 'Bicicletário salvo com sucesso!');
            if($this->isAjax){
               return $this->controller->renderAjax('view', ['bike_keeper' => $bike_keeper, 'bike_keeper_user'=>$bike_keeper_user, 'isAjax'=>$this->isAjax]);
               \Yii::$app->end(0);
            }
            return $this->controller->render('view', ['bike_keeper' => $bike_keeper, 'bike_keeper_user'=>$bike_keeper_user,'isAjax'=>$this->isAjax]);
           }
        }
        
//        return ($this->isAjax)? 
//        $this->controller->renderAjax("_bike-keeper-form", ['bike_keeper'=>$bike_keeper, 'bike_keeper_user'=>$bike_keeper_user ,'isAjax'=>$this->isAjax])
//        :
//        $this->controller->render("_bike-keeper-form", ['bike_keeper'=>$bike_keeper, 'bike_keeper_user'=>$bike_keeper_user ,'isAjax'=>$this->isAjax]);
          return $this->controller->renderAjax("_bike_keeper_form", ['bike_keeper'=>$bike_keeper, 'bike_keeper_user'=>$bike_keeper_user ,'isAjax'=>$this->isAjax]);      
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