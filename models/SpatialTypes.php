<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "spatial_types".
 *
 * @property integer $id
 * @property string $description
 */
class SpatialTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'spatial_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Description',
        ];
    }
    public function getAlertTypes()
    {
        return $this->hasMany(AlertTypes::className(), ['id' => 'alert_types_id'])
            ->viaTable(AlertTypesSpatialTypes::tableName(), ['spatial_types_id' => 'id']);
    }
    /**
     * @inheritdoc
     * @return SpatialTypesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SpatialTypesQuery(get_called_class());
    }
}
