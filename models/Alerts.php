<?php

namespace app\models;

use Yii;
use app\models\Users;
use marcusfaccion\db\GeoJSON_ActiveRecord;
use marcusfaccion\helpers\String;
use yii\helpers\Json;
use app\models\AlertComments;

/**
 * This is the model class for table "alerts".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $type_id
 * @property integer $user_id
 * @property string $created_date
 * @property integer $likes
 * @property integer $dislikes
 * @property integer $geometry_id
 * @property string $updated_date
 * @property string $duration_date
 * @property integer $enable
 *
 * @property Geometries $geometry
 */
class Alerts extends GeoJSON_ActiveRecord
{
    
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alerts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'required', 'on' => self::SCENARIO_CREATE],
            [['description', 'geom'], 'string'],
            [['type_id', 'user_id', 'likes', 'dislikes', 'enable'], 'integer'],
            [['created_date', 'updated_date', 'duration_date'], 'safe'],
            [['title'], 'string', 'max' => 40],
            [['address'], 'string'],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => AlertTypes::className(), 'targetAttribute' => ['type_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }
    
    // define os atributos seguros "safe" para massive atribuition via $model->attributes 
    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['title', 'description', 'address', 'duration_date', 'type_id', 'user_id', 'geojson_string'],
            self::SCENARIO_UPDATE => ['title', 'description', 'address', 'duration_date', 'type_id', 'user_id', 'geojson_string'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Título'),
            'description' => Yii::t('app', 'Descrição'),
            'address'=>'Endereço',
            'type_id' => Yii::t('app', 'Tipo'),
            'user_id' => Yii::t('app', 'Colaborador'),
            'created_date' => Yii::t('app', 'Criado'),
            'likes' => Yii::t('app', 'Avaliações positivas'),
            'dislikes' => Yii::t('app', 'Avaliações negativas'),
            'geom' => Yii::t('app', 'Geometria'),
            'updated_date' => Yii::t('app', 'Atualizado'),
            'duration_date' => Yii::t('app', 'Dura até'),
            'enable' => Yii::t('app', 'Ativado'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(AlertTypes::className(), ['id' => 'type_id']);
    }
     /**
    * @return \yii\db\ActiveQuery
    */
   public function getUser()
   {
       return $this->hasOne(Users::className(), ['id' => 'user_id']);
   }
   
    /**
    * @return \yii\db\ActiveQuery
    */
   public function getComments()
   {
       return $this->hasMany(AlertComments::className(), ['alert_id' => 'id'])->orderBy('created_date');
   }
   
    /**
     * @inheritdoc
     * @return AlertsQuery the active query usada por esta ActiveRecord class.
     */
    public static function find()
    {
        return new AlertsQuery(get_called_class());
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNonExistence()
    {
        return $this->hasMany(UserAlertNonexistence::className(), ['alert_id' => 'id']);
        //return $this->hasMany(UserAlertNonexistence::className(), ['alert_id' => 'id'])->orderBy('id desc');
    }
    
    /**
     * Desativa e retorna o numero de alerta afetados
     * @param mixed $alerts (array|Alerts) - array de ids ou array de objetos Alerts
     * @return type int|false
     */
    static function disableAll($alerts){
        if($alerts[0] instanceof Alerts){
            $rows = 0;
            foreach ($alerts as $alert){
                $rows += $alert->disable();
            }
          return $rows;
        }
        return Alerts::updateAll(['enable'=>0, $alerts]);
    }
    
    /**
     * Tranforma o model Alerts numa string Feature/geoJSON
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
            $this->geom = Yii::$app->db->createCommand("SELECT ST_SetSRID(ST_GeomFromGeoJSON('$this->geojson_string'),$this->srid)")->queryScalar();
            return true;
        } else {
            return false;
        }
    }
    
    public function afterFind(){
        $this->geojson_string = Yii::$app->db->createCommand("SELECT ST_AsGeoJSON('$this->geom')")->queryScalar();
        $this->geojson_array = $this->toArray();
    }
    
    public function disable(){
         $this->enable = 0;
         return $this->update(false);
     }
    
    public function enable(){
         $this->enable = 1;
         return $this->update();
     }
    
    public function addLike(){
        ++$this->likes;
        return $this->save(false);
    }
    public function delLike(){
        --$this->likes;
        return $this->save(false);
    }
    public function addDisLike(){
        ++$this->dislikes;
        return $this->save(false);
    }
    public function delDisLike(){
        --$this->dislikes;
        return $this->save(false);
    }
    static function iLike($alert_id){
        $alert = Alerts::findOne($alert_id);
        ++$alert->likes;
        return $alert->save(false);
    }
    static function iDisLike($alert_id){
        $alert = Alerts::findOne($alert_id);
        ++$alert->dislikes;
        return $alert->save(false);
    }
    
}



