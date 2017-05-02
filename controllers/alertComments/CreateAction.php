<?php
namespace app\controllers\alertComments;

use Yii;
use yii\base\Action;
use app\models\AlertComments;
use app\models\Users;

class CreateAction extends Action
{
    protected $_isAjax = false;
    
    public function run()        
    {
        
        $alert_comment = new AlertComments(['scenario'=>AlertComments::SCENARIO_CREATE]);
        $this->isAjax = \Yii::$app->request->isAjax;
        
        $alert_comment->attributes = Yii::$app->request->post('AlertComments');
        $alert_comment->created_date = date('Y-m-d H:i:s');
        $alert_comment_user = Users::findOne($alert_comment->user_id);
        
           if($alert_comment->save()){ 
            if($this->isAjax){
               return $this->controller->renderAjax('_comment', ['alert_comment' =>$alert_comment, 'alert_comment_user'=>$alert_comment_user, 'isAjax'=>$this->isAjax]);
               \Yii::$app->end(0);
            }
            return $this->controller->render('view', ['alert_comment' =>$alert_comment, 'alert_comment_user'=>$alert_comment_user, 'isAjax'=>$this->isAjax]);
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