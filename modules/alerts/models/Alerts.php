<?php

namespace app\modules\alerts\models;

use Yii;
use app\modules\user\models\Users;
use marcusfaccion\db\GeoJSON_ActiveRecord;
use yii\helpers\Json;

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
 * @property integer $unlikes
 * @property integer $geometry_id
 * @property string $updated_date
 * @property boolean $visible
 *
 * @property Geometries $geometry
 */
class Alerts extends GeoJSON_ActiveRecord
{
    
    const SCENARIO_CREATE = 'create';
    
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
            [['title', 'description'], 'required', 'on' => self::SCENARIO_CREATE],
            [['description', 'geom'], 'string'],
            [['type_id', 'user_id', 'likes', 'unlikes'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            [['visible'], 'boolean'],
            [['title'], 'string', 'max' => 40],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => AlertTypes::className(), 'targetAttribute' => ['type_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }
    
    // define os atributos seguros "safe" para massive atribuition via $model->attributes 
    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['title', 'description', 'type_id', 'user_id', 'geom'],
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
            'type_id' => Yii::t('app', 'Tipo'),
            'user_id' => Yii::t('app', 'Colaborador'),
            'created_date' => Yii::t('app', 'Criado em'),
            'likes' => Yii::t('app', 'Likes'),
            'unlikes' => Yii::t('app', 'Unlikes'),
            'geom' => Yii::t('app', 'Geometry'),
            'updated_date' => Yii::t('app', 'Updated Date'),
            'visible' => Yii::t('app', 'Visible'),
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
     * @inheritdoc
     * @return AlertsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AlertsQuery(get_called_class());
    }
    
    public function toFeature() {
        parent::toFeture();
        $this->geojson['geometry'] = Json::decode($this->geom);
        $this->geojson['properties'] = $this->attributes; //$this->getAttributes();
    }
    
    public function beforeSave($insert) {
        $a=0;
        if (parent::beforeSave($insert)) {
            $this->geom = (new \yii\db\Query)->select("ST_GeomFromGeoJSON('$this->geom')")->scalar();
            return true;
        } else {
            return false;
        }
    }
    
    /*public function afterFind(){
        $this->geom = (new \yii\db\Query())->select(["ST_AsGeoJSON($this->geom)"])->from(self::tableName())->one();
    }*/
}



