<?php
namespace app\controllers\users;

use Yii;
use yii\base\Action;
use app\models\Users;

class ChallangeAction extends Action
{
    protected $_isAjax = false;
    
    public function run()         
    {  
        $this->isAjax = Yii::$app->request->isAjax;
        
        $steps = 3; // Quantidade de passos para o usuário
        $step = Yii::$app->request->post('step');
        $fail = false; // controle
        $user = new Users(['scenario'=>Users::SCENARIO_NULL]);
        
        //carrega formulário
        if($step==1){
            $user = new Users(['scenario'=>Users::SCENARIO_CHALLANGE1]);
        }
        
        //reset de senha
        if($step==4){
            $user = Users::findOne(Yii::$app->request->post('Users')['id']);
            $user->scenario = Users::SCENARIO_CHALLANGE3;
            $user->attributes = Yii::$app->request->post('Users'); //senhas digitadas
            if($user->checkPasswords() & $user->save()){
                return $this->controller->renderPartial('@app/views/_html_message',['text'=>"Senha atualizada com sucesso!",'css_class'=>['parent'=>'alert alert-dismissible alert-success','text'=>'bg-success']]);
            }else{
                $user->addError('password', 'Senhas inválidas, cheque se são iguais');
                $user->password = '';
                $user->password_repeat = '';
                $step = 3; //Tela de alteração de senha
                $fail = true;
            }
        }
        //checa resposta secreta
        if($step==3 && !$fail){
            $user = Users::findOne(Yii::$app->request->post('Users')['id']);
            if(!$user->isAnswerCorrect(trim(strtolower(Yii::$app->request->post('Users')['answer'])))){
                $user->addError('answer', 'Resposta errada :(');
                $step = 2; // Tela de resposta novamente
                $fail = true;
                $user->answer = '';
            }else{
                $user->password = '';
            }
        }
        //carrega usuário segundo email digitado
        if($step==2 && !$fail){
            $user = Users::findOne(['email'=>Yii::$app->request->post('Users')['email']]);
            if(!$user){
                $user = new Users(['scenario'=>Users::SCENARIO_CHALLANGE1]);
                $user->addError('email', 'Email inexistente');
                $step = 1;
            }else{
                $user->scenario = Users::SCENARIO_CHALLANGE2;
                $user->answer = '';
            }
        }
        
        
        if($this->isAjax){
            return $this->controller->renderAjax('_challange',['steps'=>$steps,'step'=>$step, 'user'=>$user]);
            \Yii::$app->end(0);
        }
        return $this->controller->renderAjax('_challange',['steps'=>$steps,'step'=>$step, 'user'=>$user]);
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