<?php
namespace app\controllers\bikeKeepersComments;

use Yii;
use yii\base\Action;
use app\models\BikeKeeperComments;
use app\models\Users;

class CreateAction extends Action
{
    protected $_isAjax = false;
    
    public function run()        
    {
        
        $bike_keeper_comment = new BikeKeeperComments(['scenario'=>BikeKeeperComments::SCENARIO_CREATE]);
        $this->isAjax = \Yii::$app->request->isAjax;
        
        $bike_keeper_comment->attributes = Yii::$app->request->post('BikeKeeperComments');
        $bike_keeper_comment->created_date = date('Y-m-d H:i:s');
        $bike_keeper_comment_user = Users::findOne($bike_keeper_comment->user_id);
        
           if($bike_keeper_comment->save()){ 
            if($this->isAjax){
               return $this->controller->renderAjax('_comment', ['bike_keeper_comment' =>$bike_keeper_comment, 'bike_keeper_comment_user'=>$bike_keeper_comment_user, 'isAjax'=>$this->isAjax]);
               \Yii::$app->end(0);
            }
            return $this->controller->render('view', ['bike_keeper_comment' =>$bike_keeper_comment, 'bike_keeper_comment_user'=>$bike_keeper_comment_user, 'isAjax'=>$this->isAjax]);
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