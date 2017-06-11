<?php
namespace marcusfaccion\db;
use Yii;
use yii\db\ActiveRecord;
use marcusfaccion\geojson\GeoJSONInterface;
use yii\helpers\Json;
/**
 * Description of Objects
 *
 * @author Marcus
 */
abstract class GeoJSON_ActiveRecord extends ActiveRecord implements GeoJSONInterface{
        
     protected static $EPSG = [
         '3857' => 3857, // indicado para Web Mapping
         '4326' => 4326 // WGS84 - indicado para GIS
     ];
     const FEATURE = [
            'type'=>'Feature',
            'geometry'=>null,
            'properties'=>null,
            'id'=>'',
        ];


      /**
      * Spatial Reference System Identifier - SRID a ser utilizado (WGS84 - EPSG:4326, Web Mapping EPSG:3857)
      * @var integer $srid 
      */
     public $srid;
     
     /**
      * Representação em String da estrutura de dados GeoJSON em tratamento em tempo de execução
      * @var string $geojson 
      */
     public $geojson_array = null;
     public $geojson_string = null;
     
     
     /**
      * Converts the model into an array.
      * This method will first identify which fields to be included in the resulting
      * array by calling resolveFields(). It will then turn the model into an array 
      * with these fields. If $recursive is true, any embedded objects will also be
      * converted into arrays.
      * If the model implements the yii\web\Linkable interface, the resulting array
      * will also have a _link element which refers to a list of links as specified
      * by the interface.
      * 
      * @param array $fields
      * @param array $expand
      * @param boolean $recursive
      * @return array The array representation of the object.
      */
     public function toArray(array $fields = [], array $expand = [], $recursive = true){
         parent::toArray($fields, $expand, $recursive);
     }
     
     public function toFeture(){
         $this->geojson_array = self::FEATURE; 
     }
     
     public function toGeoJSON(){
        return Json::encode($this->geojson_array);
     }
     
     static function getGeoJSON(){
        return Json::encode($this->geojson_array);
     }
     
     function __construct($config = array()) {
         parent::__construct($config);
         $this->srid = self::$EPSG['3857'];
     }
     
     static function geoJSONfromGeometry($geom){
         return Yii::$app->db->createCommand("SELECT ST_AsGeoJSON('$geom')")->queryScalar();
     }
}
