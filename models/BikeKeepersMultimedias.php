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
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
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

        // define os atributos seguros "safe" para população massiva via $model->attributes 
    public function scenarios()
    {
        // verificar se multimedias (manymany relation) pode gerar erro por não ser um atributo(somente em runtime)
        return [
            self::SCENARIO_CREATE => ['bike_keepers_id', 'multimedias_id'],
            self::SCENARIO_UPDATE => ['bike_keepers_id', 'multimedias_id'],
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
