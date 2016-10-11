<?php

namespace app\modules\geometries\models;

use Yii;

/**
 * This is the model class for table "geometries".
 *
 * @property integer $id
 * @property integer $srid
 * @property string $geom
 * @property string $name
 *
 * @property Alerts[] $alerts
 */
class Geometries extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'geometries';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['srid'], 'integer'],
            [['geom'], 'string'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'srid' => Yii::t('app', 'Srid'),
            'geom' => Yii::t('app', 'Geom'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlerts()
    {
        return $this->hasMany(Alerts::className(), ['geometry_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return GeometriesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GeometriesQuery(get_called_class());
    }
}
