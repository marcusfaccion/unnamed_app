<?php
namespace app\controllers\friends;

use Yii;
use yii\base\Action;
use app\models\UserFriendshipRequests;
use app\models\Users;

class AddAction extends Action
{
    protected $_isAjax = false;
    
    public function run()        
    {
        
        $num_req = UserFriendshipRequests::find()->where(['user_id'=>Yii::$app->user->identity->id, 'requested_user_id'=>Yii::$app->request->post('UserFriendshipRequest')['requested_user_id']])->count();
        
        if($num_req==0){
            $friendship_request = new UserFriendshipRequests (['scenario'=>UserFriendshipRequests::SCENARIO_CREATE]);
            $this->isAjax = \Yii::$app->request->isAjax;

            $friendship_request->attributes = Yii::$app->request->post('UserFriendshipRequest');
            $friendship_request->user_id = Yii::$app->user->identity->id;

            $friendship_request->created_date = date('Y-m-d H:i:s');
            if($friendship_request->save()){
                Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                return $this->controller->renderPartial('@app/views/_html_message',['text'=>"Solicitação de amizade enviada com sucesso!",'css_class'=>['parent'=>'alert alert-dismissible alert-success','text'=>'bg-success']]);
            }
        }
        
        //Já existe solicitação para esse usuário
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        return $this->controller->renderPartial('@app/views/_html_message',['text'=>"Já exite uma solicitação para este usuário. Aguarde!",'css_class'=>['parent'=>'alert alert-dismissible alert-warning','text'=>'bg-warning']]);
        
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