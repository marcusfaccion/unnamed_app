<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_bike_keeper_rates".
 *
 * @property integer $user_id
 * @property integer $bike_keeper_id
 * @property string $created_date
 * @property string $updated_date
 * @property string $rating
 */
class UserBikeKeeperRates extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_bike_keeper_rates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'bike_keeper_id'], 'required'],
            [['user_id', 'bike_keeper_id'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            [['rating'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'bike_keeper_id' => 'BikeKeeper ID',
            'created_date' => 'Created Date',
            'updated_date' => 'Updated Date',
            'rating' => 'Rating',
        ];
    }

    /**
     * @inheritdoc
     * @return UserBikeKeeperRatesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserBikeKeeperRatesQuery(get_called_class());
    }
}
