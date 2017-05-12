<?php

namespace app\models;

use Yii;
use marcusfaccion\helpers\String;
use yii\helpers\Json;
use app\models\Users;
use marcusfaccion\db\GeoJSON_ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "bike_keepers".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $business_hours
 * @property integer $likes
 * @property integer $dislikes
 * @property integer $capacity
 * @property integer $used_capacity
 * @property integer $user_id
 * @property string $geom
 * @property Users $user
 * @property string $public_dir_name
 * @property real $cost
 */
class BikeKeepers extends GeoJSON_ActiveRecord
{
    
    const SCENARIO_CREATE = 'create';
    
    /**
     * Armazena em runtime as multimedias relacionadas ao model BikeKeepers
     * @var array Multimedias models 
     */
    public $multimedias;
    
    /**
     *  Variável auxiliar para upload de arquivos
     * @var UploadFile 
     */
    public $multimedia_files;
    
    /**
     *  @var string bike_keeper directory path
     */
    public $public_dir;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bike_keepers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'business_hours', 'capacity', 'multimedia_files', 'public', 'outdoor'], 'required', 'on' => self::SCENARIO_CREATE],
            [['multimedia_files'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg, avi, mp4, webm', 'maxFiles' => 4],
            [['likes', 'dislikes', 'capacity', 'used_capacity', 'user_id', 'public', 'outdoor', 'enable'], 'integer'],
            [['created_date', "updated_date"], 'safe'],
            [['title'], 'string', 'max' => 40],
            [['description', 'business_hours', 'geom'], 'string'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }
    
    // define os atributos seguros "safe" para população massiva via $model->attributes 
    public function scenarios()
    {
        // verificar se multimedias (manymany relation) pode gerar erro por não ser um atributo(somente em runtime)
        return [
            self::SCENARIO_CREATE => ['title', 'description', 'business_hours', 'multimedia_files', 'capacity', 'cost','user_id', 'geojson_string', 'public', 'outdoor', 'public_dir_name'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Nome do bicicletário',
            'likes' => 'Likes',
            'dislikes' => 'Dislikes',
            'capacity' => 'Número de vagas',
            'cost' => 'Diária cobrada',
            'multimedia_files' => 'Multimídea',
            'description' => Yii::t('app', 'Descrição'),
            'business_hours' => Yii::t('app', 'Horário de funcionamento'),
            'used_capacity' => 'Used Capacity',
            'user_id' => 'User ID',
            'enable'=>'Ativado',
            'outdoor'=>'Ao ar livre',
            'public'=>'É de uso público?',
            'public2'=>'Público',
            'public_dir_name'=>'Diretório público',
        ];
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
        return $this->hasMany(BikeKeeperComments::className(), ['bike_keeper_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMultimedias()
    {
        return $this->hasMany(Multimedias::className(), ['id' => 'bike_keepers_id'])
            ->viaTable(BikeKeepersMultimedias::tableName(), ['multimedias_id' => 'id']);
    }
    
    /**
     * Tranforma o model BikeKeepers numa string Feature/geoJSON
     * @param array $attributes nomes dos atributos a retornar, se null retornará todos
     * @return type
     */
    public function toFeature($attributes=null) {
        parent::toFeture(); 
        $this->geojson_array['geometry'] = Json::decode($this->geojson_string);
        $this->geojson_array['properties'] = $this->getAttributes($attributes);
        
        // propridades adicionais para o marcador do alerta
        $this->geojson_array['properties'] = array_merge($this->geojson_array['properties'],[
            'type_desc' => 'bicicletario',
            'type_desc_en' => 'bicicletario',
        ]);
        
        $this->geojson_array['id'] = strtolower($this->formName()).'_'.$this->id;
        return $this->toGeoJSON();
    }
    
    public function toArray(array $fields = [], array $expand = [], $recursive = true) {
        //parent::toArray($fields, $expand, $recursive = true);
        $fields = empty($fields)?null:$fields;
        $this->geojson_array['geometry'] = Json::decode($this->geojson_string);
        $this->geojson_array['properties'] = $this->getAttributes($fields); //$this->getAttributes();
        
        // propridades adicionais para o marcador do alerta
        $this->geojson_array['properties'] = array_merge($this->geojson_array['properties'],[
            'type_desc' => 'bicicletario',
            'type_desc_en' => 'bicicletario',
        ]);
        
        $this->geojson_array['id'] = strtolower($this->formName()).'_'.$this->id;
        return $this->geojson_array;
    }
    
    public function getPublicDirName(){
        $last_inserted = $last_inserted_id = null;
        if($this->isNewRecord){
            $last_inserted = BikeKeepers::find()->orderBy('id desc')->one();
            $last_inserted_id = $last_inserted ? $last_inserted->id : 0;
            $this->public_dir_name = Yii::$app->security->hashData($last_inserted_id+1, Yii::$app->security->getAppSecret());
            return $this->public_dir_name;
        }
        return $this->public_dir_name;
    }
    
    public function upload()
    {
        if ($this->validate()) {
            $this->public_dir = Yii::getAlias('@bike_keepers_dir_path').'/'.$this->getPublicDirName();
            if(!is_dir($this->public_dir)){
                mkdir($this->public_dir.'/images', 775, true);
            }
            foreach ($this->multimedia_files as $file) {
                $multimedia = new Multimedias;
                $multimedia->type_id = MultimediaTypes::getIdByMimeType($file->type);
                $multimedia->created_date = date('Y-m-d H:i:s');
                $saved = $file->saveAs($this->public_dir.'/images/' . $file->baseName . '.' . $file->extension, false);
                if($saved){
                    $multimedia->src = $this->public_dir.'/images/' . $file->baseName . '.' . $file->extension;
                    if($multimedia->save())
                        $this->multimedias[] = $multimedia;
                    else{
                        unset($this->multimedia_files);
                        unlink($multimedia->src);
                    }
                }
            }
            return true;
        }else {
            return false;
        }
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
   
    /**
     * Desativa e retorna o numero de bicicletários afetados
     * @param mixed $bike_keepers (array|BikeKeepers) - array de ids ou array de objetos BikeKeepers
     * @return type int|false
     */
    static function disableAll($bike_keepers){
        if($bike_keepers[0] instanceof BikeBikeKeepers){
            $rows = 0;
            foreach ($bike_keepers as $bike_keeper){
                $rows += $bike_keeper->disable();
            }
          return $rows;
        }
        return BikeKeepers::updateAll(['enable'=>0, $bike_keepers]);
    }
    
    public function disable(){
         $this->enable = 0;
         return $this->update(false);
     }
    
    public function enable(){
         $this->enable = 1;
         return $this->update();
     }
}
