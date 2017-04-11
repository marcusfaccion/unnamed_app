<?php
namespace app\controllers\friends;

use Yii;
use yii\base\Action;
use app\models\UserFriendships;
use app\models\UserFriendshipRequests;

class AceptRequestAction extends Action
{
    protected $_isAjax = false;
    
    public function run()        
    {
        $this->isAjax = Yii::$app->request->isAjax;
        
        if(isset(Yii::$app->request->post('UserFriendships')['friend_user_id'])){
            
            // Obtém o objeto UserFriendshipRequests caso exista que representa a solicitação da amizade em questão
            $request = UserFriendshipRequests::find()->where(['user_id'=>Yii::$app->request->post('UserFriendships')['friend_user_id'],
                                          'requested_user_id'=>Yii::$app->user->identity->id])->one();
            if($request){
                //criando a amizade
                $friend_ship = new UserFriendships(['scenario'=>UserFriendships::SCENARIO_CREATE]);
                $friend_ship->user_id = $request->requested_user_id;
                $friend_ship->friend_user_id = $request->user_id;
                $friend_ship->created_date = date('Y-m-d H:i:s');

                if($friend_ship->save()){
                    //Depois de criada a amizade exclui-se a solicitação
                    $request->delete();
                    
                    Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                    return $this->controller->renderPartial('@app/views/_html_message',['text'=>"Solicitação aceita. Agora vocês são amigos!",'css_class'=>['parent'=>'alert alert-dismissible alert-success','text'=>'bg-success']]);
                }
            }
                
            Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
            return $this->controller->renderPartial('@app/views/_html_message',['text'=>"Erro na solicitação!",'css_class'=>['parent'=>'alert alert-dismissible alert-warning','text'=>'bg-warning']]);
        }  
    }
    
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