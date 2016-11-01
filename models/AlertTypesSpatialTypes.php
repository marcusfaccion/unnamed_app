<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "alert_types_spatial_types".
 *
 * @property integer $alert_types_id
 * @property integer $spatial_types_id
 */
class AlertTypesSpatialTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alert_types_spatial_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alert_types_id', 'spatial_types_id'], 'required'],
            [['alert_types_id', 'spatial_types_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'alert_types_id' => 'Alert Types ID',
            'spatial_types_id' => 'Spatial Types ID',
        ];
    }

    /**
     * @inheritdoc
     * @return AlertTypesSpatialTypesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AlertTypesSpatialTypesQuery(get_called_class());
    }
}
