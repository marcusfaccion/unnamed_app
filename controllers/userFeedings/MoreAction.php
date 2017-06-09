<?php
namespace app\controllers\userFeedings;

use Yii;
use yii\base\Action;
use app\models\Users;
use app\models\UserFeedings;
use app\models\Alerts;
use app\models\BikeKeepers;
use app\models\UserNavigationRoutes;

class MoreAction extends Action
{
    protected $_isAjax = false;
    
    public function run($id, $news=0)         
    {  
        $this->isAjax = Yii::$app->request->isAjax;
        
        $user = Users::findOne(Yii::$app->user->identity->id);
        // Obtendo os itens do feeding do usuário composto pelos compartilhamentos de seus amigos e os seus próprios
        $feedings = UserFeedings::find()->where('user_id in (select * from user_'.$user->id.'_friends_id)')
                ->orWhere(['user_id'=>$user->id])
                ->andWhere("id ".(($news==1)?"> $id":"< $id"))
                ->orderBy('created_date desc')
                ->limit(4)
                ->all();

        if($this->isAjax){
            return $this->controller->renderAjax('_more',['feedings'=>$feedings, 'user'=>$user]);
            \Yii::$app->end(0);
        }
        return $this->controller->renderPartial('_more',['feedings'=>$feedings, 'user'=>$user]);
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