<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "alerts_types".
 *
 * @property integer $id
 * @property string $description
 * @property integer $parent_type_id
 *
 * @property AlertTypes $parentType
 * @property AlertTypes[] $alertsTypes
 */
class AlertTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alert_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_type_id'], 'integer'],
            [['description'], 'string', 'max' => 50],
            [['parent_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => AlertTypes::className(), 'targetAttribute' => ['parent_type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'description' => Yii::t('app', 'Description'),
            'parent_type_id' => Yii::t('app', 'Parent Type ID'),
        ];
    }
    
    /**
     * @inheritdoc
     * @return AlertTypesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AlertTypesQuery(get_called_class());
    }
    
    public function getSpatialTypes()
    {
        return $this->hasMany(SpatialTypes::className(), ['id' => 'spatial_types_id'])
            ->viaTable(AlertTypesSpatialTypes::tableName(), ['alert_types_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(AlertTypes::className(), ['id' => 'parent_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlertTypes()
    {
        return $this->hasMany(AlertTypes::className(), ['parent_type_id' => 'id']);
    }

    public function iconfileName($size='80', $ext='.png'){
        return strtolower(str_replace(" ", "_", $this->description.'_'.$size.$ext));
    }
}
