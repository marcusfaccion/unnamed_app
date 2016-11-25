<?php

namespace app\models;

use Yii;
use marcusfaccion\db\GeoJSON_ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "bike_keepers".
 *
 * @property integer $id
 * @property string $title
 * @property integer $likes
 * @property integer $unlikes
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
            [['title', 'description', 'capacity', 'multimedia_files', 'public', 'outdoor'], 'required', 'on' => self::SCENARIO_CREATE],
            [['multimedia_files'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg, avi, mp4, webm', 'maxFiles' => 4],
            [['likes', 'unlikes', 'capacity', 'used_capacity', 'user_id', 'public', 'outdoor', 'enable'], 'integer'],
            [['created_date'], 'safe'],
            [['title'], 'string', 'max' => 40],
            [['description','geom'], 'string'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }
    
    // define os atributos seguros "safe" para população massiva via $model->attributes 
    public function scenarios()
    {
        // verificar se multimedias (manymany relation) pode gerar erro por não ser um atributo(somente em runtime)
        return [
            self::SCENARIO_CREATE => ['title', 'description', 'multimedia_files', 'capacity', 'cost','user_id', 'geojson_string', 'public', 'outdoor', 'public_dir_name'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Nome',
            'likes' => 'Likes',
            'unlikes' => 'Unlikes',
            'capacity' => 'Número de vagas',
            'cost' => 'Preço',
            'multimedia_files' => 'Multimídea',
            'description' => Yii::t('app', 'Descrição'),
            'used_capacity' => 'Used Capacity',
            'user_id' => 'User ID',
            'enable'=>'Ativado',
            'outdoor'=>'Ao ar livre',
            'public'=>'É Pago?',
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
    
    public function getPublicDirName(){
        if($this->isNewRecord){
            $this->public_dir_name = md5(Yii::$app->security->generateRandomString());
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
            foreach ($this->multimidia_files as $file) {
                $file->saveAs($this->public_dir.'/id_bikeeper_criptografado/' . $file->baseName . '.' . $file->extension, false);
            }
            return true;
        }else {
            return false;
        }
    }
}
