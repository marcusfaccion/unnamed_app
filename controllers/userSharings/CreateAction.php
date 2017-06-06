<?php
namespace app\controllers\userSharings;

use Yii;
use yii\base\Action;
use app\models\UserSharings;
use app\models\UserSharingTypes;
use app\models\UserFeedings;
use app\models\UserNavigationRoutes;

class CreateAction extends Action
{
    protected $_isAjax = false;
    
    public function run()        
    {  
        $this->isAjax = Yii::$app->request->isAjax;
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
        
        $content = null; // Conteúdo sendo compartilhado
        
        $user_sharing = new UserSharings(['scenario'=>  UserSharings::SCENARIO_CREATE]);
        $user_feeding = new UserFeedings(['scenario'=> UserFeedings::SCENARIO_CREATE]);
        
        $user_route = new UserNavigationRoutes(['scenario'=> UserNavigationRoutes::SCENARIO_CREATE]);// caso o usuário esteja compartilhando uma rota
        
        $sharing_type = UserSharingTypes::findOne(Yii::$app->request->post('UserSharings')['sharing_type_id']);
        
        $user_sharing->attributes = Yii::$app->request->post('UserSharings');//dados do formulário 
        $user_feeding->attributes = Yii::$app->request->post('UserFeedings');//dados do formulário 
        
        switch($sharing_type->name){
            case 'alert':
                break;
            case 'bike keeper':
                break;
            case 'route':
                $content = $user_route;
                $content->attributes = Yii::$app->request->post('UserNavigationRoutes');//dados do formulário 
                $content->user_id = $user_sharing->user_id;
                $content->duration = round($content->duration);
                $content->origin_geojson = $content->origin_geom;
                $content->destination_geojson = $content->destination_geom;
                $content->line_string_geojson = $content->line_string_geom;
                if($content->save()){
                    //Executando função espacial para obter a distância da LineString da rota em metros
                    

                     ######################################
                    //Esta dando erro aqui escapar o asterisco
                    #######################################
                    
                    //$content->distance = UserNavigationRoutes::find()->select('ST_LengthSpheroid(line_string_geom, \'SPHEROID["WGS 84",6378137,298.257223563]\')')->where(['id'=>$content->id])->scalar();
                    $content->distance = Yii::$app->db->createCommand("SELECT ST_LengthSpheroid('$content->line_string_geom', 'SPHEROID[\"WGS 84\",6378137,298.257223563]')")->queryScalar();
                    $content->save(false);
                }
                break;
        }
        
        //Guardando o id do conteúdo e data de criação
        $user_sharing->content_id = $content->id;
        $user_sharing->created_date = date('Y-m-d H:i:s');
        
        if($user_sharing->save()){
            $user_feeding->user_sharing_id = $user_sharing->id;
            $user_feeding->user_id = $user_sharing->user_id;
            $user_feeding->created_date = date('Y-m-d H:i:s');
            if($user_feeding->save()){
                $user_sharing->user_feeding_id = $user_feeding->id; //Relacionando o compartilhamento com o Feed
                $user_sharing->save(false);
                if($this->isAjax){
                    if(in_array($sharing_type->name, ['route'])){ // somente rota é um nome feminio em pt-BR alerta e bicicletário não 
                        $gender = 'female';
                    }
                    return $this->controller->renderPartial('@app/views/_html_message',['text'=>Yii::t('app', $sharing_type->name).' '. Yii::t('app', '{gender,select,female{compartilhada} male{compartilhado} other{compartilhado}}', ['gender'=>$gender
])." com sucesso!",'css_class'=>['parent'=>'alert alert-dismissible alert-success','text'=>'bg-success']]);
                    \Yii::$app->end(0);
                }
            }
                
        }
        
        if($this->isAjax){
            return Yii::$app->controller->renderAjax('@app/views/account/_form', ['user_feeding'=>$user_feeding]);
            \Yii::$app->end(0);
        }
        return Yii::$app->controller->renderFile('@app/views/account/_form.php', ['user_feeding'=>$user_feeding]);
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