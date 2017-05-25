<?php

namespace app\models;

use Yii;
use marcusfaccion\db\GeoJSON_ActiveRecord;
use app\models\Users;

/**
 * This is the model class for table "user_navigation_routes".
 *
 * @property integer $id
 * @property string $origin_geom
 * @property string $destination_geom
 * @property string $line_string_geom
 */
class UserNavigationRoutes extends GeoJSON_ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    
    public $origin_geojson;
    public $destination_geojson;
    public $line_string_geojson;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_navigation_routes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['origin_geom', 'destination_geom', 'line_string_geom'], 'string'],
            [['user_id', 'duration'], 'integer'],
            [['distance'], 'double'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'origin_geom' => 'Origin',
            'destination_geom' => 'Destination',
            'line_string_geom' => 'Line String',
            'distance'=>'Distância',
            'duration'=>'Duração',
            'user_id'=>'Usuário',
        ];
    }

    /**
     * @inheritdoc
     * @return UserNavigationRoutesQuery the active query used by this AR class.
     */
    
    
    // define os atributos seguros "safe" para população massiva via $model->attributes 
    public function scenarios()
    {
        // verificar se multimedias (manymany relation) pode gerar erro por não ser um atributo(somente em runtime)
        return [
            self::SCENARIO_CREATE => ['origin_geom', 'destination_geom', 'duration', 'distance', 'user_id', 'line_string_geom',],
            self::SCENARIO_UPDATE => ['origin_geom', 'destination_geom', 'duration', 'distance', 'user_id','line_string_geom',],
        ];
    }
   
    public static function find()
    {
        return new UserNavigationRoutesQuery(get_called_class());
    }

      /**
    * @return \yii\db\ActiveQuery
    */
   public function getUser()
   {
       return $this->hasOne(Users::className(), ['id' => 'user_id']);
   }
    
   /**
     * Tranforma o model UserNavigationRoutes numa string Feature/geoJSON
     * @param array $attributes nomes dos atributos a retornar, se null retornará todos
     * @return type
     */
    public function toFeature($attributes=null) {
        parent::toFeture();
        $type = $this->type;
        $this->geojson_array['geometry'] = Json::decode($this->geojson_string);
        $this->geojson_array['properties'] = $this->getAttributes($attributes);
        
        // propridades adicionais para o marcador do alerta
        $this->geojson_array['properties'] = array_merge($this->geojson_array['properties'],[
            'type_desc' => $type->description,
            'type_desc_en' =>  str_replace(' ', '_', String::changeChars(strtolower($type->description), String::PTBR_DIACR_SEARCH, String::PTBR_DIACR_REPLACE)),
        ]);
        
        $this->geojson_array['id'] = strtolower($this->formName()).'_'.$this->type_id."_$this->id";
        return $this->toGeoJSON();
    }
    
    public function toArray(array $fields = [], array $expand = [], $recursive = true) {
        //parent::toArray($fields, $expand, $recursive = true);
        $type = $this->type;
        $fields = empty($fields)?null:$fields;
        $this->geojson_array['geometry'] = Json::decode($this->geojson_string);
        $this->geojson_array['properties'] = $this->getAttributes($fields); //$this->getAttributes();
        
        // propridades adicionais para o marcador do alerta
        $this->geojson_array['properties'] = array_merge($this->geojson_array['properties'],[
            'type_desc' => $type->description,
            'type_desc_en' =>String::changeChars(strtolower($type->description), String::PTBR_DIACR_SEARCH, String::PTBR_DIACR_REPLACE),
        ]);
        
        $this->geojson_array['id'] = strtolower($this->formName()).'_'.$this->type_id."_$this->id";
        return $this->geojson_array;
    }
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            //$this->geom = (new \yii\db\Query)->select("ST_SetSRID(ST_GeomFromGeoJSON('$this->geom'),$this->srid)")->scalar();
            $this->origin_geom = Yii::$app->db->createCommand("SELECT ST_SetSRID(ST_GeomFromGeoJSON('$this->origin_geojson'),$this->srid)")->queryScalar();
            $this->destination_geom = Yii::$app->db->createCommand("SELECT ST_SetSRID(ST_GeomFromGeoJSON('$this->destination_geojson'),$this->srid)")->queryScalar();
            // Quando um valor do tipo geometry no postgre se torna grande demais ele deixa de ser visível como string
            $this->line_string_geom = Yii::$app->db->createCommand("SELECT ST_SetSRID(ST_GeomFromGeoJSON('$this->line_string_geojson'),$this->srid)")->queryScalar();
            return true;
        } else {
            return false;
        }
    }
}
