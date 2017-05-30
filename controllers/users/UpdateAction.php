<?php
namespace app\controllers\users;

use Yii;
use yii\base\Action;
use app\models\Users;
use yii\web\UploadedFile;

class UpdateAction extends Action
{
    protected $_isAjax = false;
    
    public function run()        
    {  
        $this->isAjax = Yii::$app->request->isAjax;
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
        
        $user = Users::findOne(Yii::$app->request->post('Users')['id']);
        $user_aux = Users::findOne(Yii::$app->request->post('Users')['id']);
        
        $user->setScenario(Users::SCENARIO_UPDATE); //Senário update
        
        $user->attributes = Yii::$app->request->post('Users');//dados do formulário 
        
        if(trim($user->password)===''&&trim($user->password_repeat)===''){
            //Se usuário não alterar a senha, devolve a senha que havia sido sobrescrita por vazio
            $user->password = $user_aux->password;
            $user->password_repeat = $user_aux->password;
        }
        
        $user->avatar_file = UploadedFile::getInstance($user, 'avatar_file'); //uploadfile input
        
        $user->full_name = $user->first_name.' '.$user->last_name;
        
        if($user->checkPasswords()){
            if($user->save()){
                Yii::$app->session->setFlash('successfully-saved','Dados atualizados com sucesso!');
                if($user->avatar_file){
                    $user->upload(); // upload do avatar
                }
            }
        }else{
            $user->addError('password', 'Senhas inválidas, cheque se são iguais');
            $user->validate(); //para mostrar outros caso existam
        }
        
        $user->password = ''; //ocultando a senha
        $user->password_repeat = ''; //ocultando confirmação de senha
        
        if($this->isAjax){
            return Yii::$app->controller->renderAjax('@app/views/account/_form', ['user'=>$user]);
            \Yii::$app->end(0);
        }
        return Yii::$app->controller->renderFile('@app/views/account/_form.php', ['user'=>$user]);
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