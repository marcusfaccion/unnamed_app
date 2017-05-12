<?php
namespace app\controllers\bikeKeepers;

use Yii;
use yii\base\Action;
use app\models\Bikekeepers;
use app\models\UserBikekeeperRates;

class IlikeAction extends Action
{
    protected $_isAjax = false;
    
    public function run()        
    {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        
        $this->isAjax = Yii::$app->request->isAjax;
        $bike_keeper = Bikekeepers::findOne(Yii::$app->request->post('BikeKeepers')['id']);
        $bike_keeper->likes = Yii::$app->request->post('BikeKeepers')['likes'];
        
        if(Yii::$app->request->post('BikeKeepers')['avaliated']){
            // Carrega a aviliação do usuário caso exista
            $user_rating = UserBikekeeperRates::findOne(['user_id'=>Yii::$app->user->identity->id,'bike_keeper_id'=>$bike_keeper->id]);
            
            if($user_rating){
                $user_rating->rating = 'likes';
                $user_rating->updated_date = date('Y-m-d H:i:s');
            }else{
                // Usuário não avaliou ainda esse alerta
                $user_rating = new UserBikekeeperRates();
                $user_rating->user_id = Yii::$app->user->identity->id;
                $user_rating->bike_keeper_id = $bike_keeper->id;
                $user_rating->created_date = date('Y-m-d H:i:s');
                $user_rating->rating = 'likes';
                $user_rating->save(false);
            }
        }else{
            // excluir a avaliação do usuário
            UserBikekeeperRates::findOne(['user_id'=>Yii::$app->user->identity->id,'bike_keeper_id'=>$bike_keeper->id])->delete();
        }
        
         if($bike_keeper->save(false)){
            
            if($this->isAjax){
               return $this->controller->renderPartial('@app/views/_scalar_return',['scalar'=>$bike_keeper->likes]);
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