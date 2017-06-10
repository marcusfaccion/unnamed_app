<?php
namespace app\controllers\routes;

use Yii;
use yii\base\Action;
use app\models\Bikekeepers;
use app\models\UserNavigationRoutes;
use app\models\Alerts;
use yii\helpers\Json;

class PlanAction extends Action
{
    protected $_isAjax = false;
    
    public function run()        
    {  
        $this->isAjax = Yii::$app->request->isAjax;
        
        $i = 0;
        $alerts = $_alerts = $bike_keepers = $_bike_keepers = $routes = [];
        
        $Routes = Yii::$app->request->post('Routes');
        if($Routes['multiRoutes']){
           unset($Routes['routes'][1]);
        }
        
        foreach($Routes['routes'] as $route){
            $tmp_route = new UserNavigationRoutes(['scenario'=>UserNavigationRoutes::SCENARIO_CREATE]);
            $tmp_route->origin_geojson = $Routes['origin_geojson'];
            $tmp_route->destination_geojson = $Routes['destination_geojson'];
            $tmp_route->line_string_geojson = $route['geometry'];
            $tmp_route->duration = $route['duration']>=60?$route['duration']:100;//correção de bug da API directions da Mapbox duração minima será 60s
            $tmp_route->distance = $route['distance'];
            $tmp_route->user_id = Yii::$app->user->identity->id;
            if($tmp_route->save()){
    //            $alerts = \app\models\Alerts::find()
    //                    ->from([\app\models\Alerts::tableName().' a', UserNavigationRoutes::tableName(). ' route'])
    //                    ->where("route.id=$tmp_route->id")
    //                    ->andWhere("ST_Distance_Spheroid(route.line_string, a.geom, 'SPHEROID[\"WGS 84\",6378137,298.257223563]')<= 10.0")
    //                    ->orderBy("ST_Line_Locate_Point(route.line_string, \"a\".\"geom\"), ST_Distance_Spheroid(route.line_string, a.geom, 'SPHEROID[\"WGS 84\",6378137,298.257223563]')")
    //                    ->all();
                
                //Obtém todos os alertas que estão a uma distância de até 20m da Line String(plotada no mapa) da rota
                $alerts[$i] = Alerts::findBySql(
                        '
                          SELECT * FROM '.Alerts::tableName().' a, '.UserNavigationRoutes::tableName().' route WHERE (route.id='.$tmp_route->id.') AND a.enable=1 AND  
                              (ST_Distance_Spheroid(route.line_string_geom, a.geom, \'SPHEROID["WGS 84",6378137,298.257223563]\')<= 12.0) 
                              ORDER BY ST_Line_Locate_Point(route.line_string_geom, a.geom), ST_Distance_Spheroid(route.line_string_geom, a.geom, \'SPHEROID["WGS 84", 6378137, 298.257223563]\') ASC
                        ')->all();
                //Obtém todos os bicicletários que estão a uma distância de até 1km do destino da rota
                if(count($bike_keepers)==0){
                    $bike_keepers = BikeKeepers::findBySql(
                        '
                          SELECT * FROM '.BikeKeepers::tableName().' b, '.UserNavigationRoutes::tableName().' route WHERE (route.id='.$tmp_route->id.') AND b.enable=1 AND  is_open=1 AND
                              (ST_Distance_Spheroid(route.destination_geom, b.geom, \'SPHEROID["WGS 84",6378137,298.257223563]\')<= 1000.0) 
                              ORDER BY ST_Distance_Spheroid(route.destination_geom, b.geom, \'SPHEROID["WGS 84", 6378137, 298.257223563]\') ASC
                        ')->all();
                }
                //Guarda a rota
                $routes[$i] = $tmp_route;
                //Deleta a rota temporária do banco de dados
                $tmp_route->delete();
                ++$i;
            }
        }
        //para Detalhamento do plano de rota   
        for($i=0;$i<count($alerts);++$i){
            for($j=0;$j<count($alerts[$i]);++$j){
                switch($alerts[$i][$j]->type->id){
                    case 1:
                        $_alerts[$i]['generico'][] = $alerts[$i][$j];
                        break;
                    case 2:
                        $_alerts[$i]['perigo_na_via'][] = $alerts[$i][$j];
                        break;
                    case 3:
                        $_alerts[$i]['roubos_e_furtos'][] = $alerts[$i][$j];
                        break;
                    case 4:
                        $_alerts[$i]['interdicoes'][] = $alerts[$i][$j];
                        break;
                }
            }
        }
        //para Detalhamento do plano de rota
        foreach($bike_keepers as $bike_keeper){
            if($bike_keeper->public){
                $_bike_keepers['public'][] = $bike_keeper;
            }else{
                $_bike_keepers['nonpublic'][] = $bike_keeper;
            }
        }
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
        // LineString da rota
        //$teste = Yii::$app->db->createCommand("SELECT ST_AsGeoJSON('".UserNavigationRoutes::findOne(23)->line_string_geom."')")->queryScalar(); 
        
        if($this->isAjax){
            return $this->controller->renderAjax('_plan',['alerts'=>$alerts, '_alerts'=>$_alerts, 'bike_keepers'=>$bike_keepers, '_bike_keepers'=>$_bike_keepers,'routes'=>$routes]);
            \Yii::$app->end(0);
        }
        
        return $this->controller->renderPartial('_plan',['alerts'=>$alerts, '_alerts'=>$_alerts, 'bike_keepers'=>$bike_keepers, '_bike_keepers'=>$_bike_keepers, 'routes'=>$routes]);
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