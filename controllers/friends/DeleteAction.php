<?php
namespace app\controllers\friends;

use Yii;
use yii\base\Action;
use app\models\UserFriendships;

class DeleteAction extends Action
{
    protected $_isAjax = false;
    
    public function run()        
    {
        
         if(isset(Yii::$app->request->post('UserFriendships')['friend_user_id'])){
            
            // Obtém o(s) objeto(s) da amizade em questão
            
            //Caso1 Usuário dono da amizade é o que está logado no momento da execução 
            $friend_ship_case1 = UserFriendships::find()->where(['user_id'=>Yii::$app->user->identity->id,'friend_user_id'=>Yii::$app->request->post('UserFriendships')['friend_user_id']])->one();
            //Caso2 Usuário dono da amizade não é o que está logado no momento da execução
            $friend_ship_case2 = UserFriendships::find()->where(['user_id'=>Yii::$app->request->post('UserFriendships')['friend_user_id'], 'friend_user_id'=>Yii::$app->user->identity->id])->one();
            
            $delete = true;
            if($friend_ship_case1){
                if($friend_ship_case1->delete()==false)
                    $delete = $delete & false;
            }
            
            if($friend_ship_case2){
                if($friend_ship_case2->delete()==false)
                    $delete = $delete & false;
            }
                
            Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
            if($delete)
                return $this->controller->renderPartial('@app/views/_html_message',['text'=>"Operação concluída com sucesso. Vocês não são mais amigos!",'css_class'=>['parent'=>'alert alert-dismissible alert-success','text'=>'bg-success']]);
            else
                return $this->controller->renderPartial('@app/views/_html_message',['text'=>"Ocorreu um erro na operação.",'css_class'=>['parent'=>'alert alert-dismissible alert-warning','text'=>'bg-warning']]);
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