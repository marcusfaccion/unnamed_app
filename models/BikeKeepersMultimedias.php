<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bike_keepers_multimedias".
 *
 * @property integer $bike_keepers_id
 * @property integer $multimedias_id
 */
class BikeKeepersMultimedias extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bike_keepers_multimedias';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bike_keepers_id', 'multimedias_id'], 'required'],
            [['bike_keepers_id', 'multimedias_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bike_keepers_id' => 'Bike Keeper',
            'multimedias_id' => 'Multimedia',
        ];
    }
}
