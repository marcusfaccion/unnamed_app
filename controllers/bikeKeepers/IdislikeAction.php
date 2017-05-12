<?php
namespace app\controllers\bikeKeepers;

use Yii;
use yii\base\Action;
use app\models\BikeKeepers;
use app\models\UserBikeKeeperRates;

class IdislikeAction extends Action
{
    protected $_isAjax = false;
    
    public function run()        
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        
        $this->isAjax = \Yii::$app->request->isAjax;
        
        $bike_keeper = BikeKeepers::findOne(Yii::$app->request->post('BikeKeepers')['id']);
        $bike_keeper->dislikes = Yii::$app->request->post('BikeKeepers')['dislikes'];
        
        if(Yii::$app->request->post('BikeKeepers')['avaliated']){
            // Carrega a aviliação do usuário caso exista
            $user_rating = UserBikeKeeperRates::findOne(['user_id'=>Yii::$app->user->identity->id,'bike_keeper_id'=>$bike_keeper->id]);
            
            if($user_rating){
                $user_rating->rating = 'dislikes';
                $user_rating->updated_date = date('Y-m-d H:i:s');
            }else{
                // Usuário não avaliou ainda esse bicicletário
                $user_rating = new UserBikeKeeperRates();
                $user_rating->user_id = Yii::$app->user->identity->id;
                $user_rating->bike_keeper_id = $bike_keeper->id;
                $user_rating->created_date = date('Y-m-d H:i:s');
                $user_rating->rating = 'dislikes';
                $user_rating->save(false);
            }
        }else{
            // excluir a avaliação do usuário
            UserBikeKeeperRates::findOne(['user_id'=>Yii::$app->user->identity->id,'bike_keeper_id'=>$bike_keeper->id])->delete();
        }

        
        if($bike_keeper->save(false)){
            
            if($this->isAjax){
               return $this->controller->renderPartial('@app/views/_scalar_return',['scalar'=>$bike_keeper->dislikes]);
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