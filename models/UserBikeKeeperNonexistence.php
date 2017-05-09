<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_bike_keeper_existence".
 *
 * @property integer $user_id
 * @property integer $bike_keeper_id
 * @property string $created_date
 */
class UserBikeKeeperNonexistence extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_bike_keeper_nonexistence';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'bike_keeper_id'], 'required'],
            [['user_id', 'bike_keeper_id'], 'integer'],
            [['created_date'], 'safe'],
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
        ];
    }

    /**
     * @inheritdoc
     * @return UserBikeKeeperNonexistenceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserBikeKeeperNonexistenceQuery(get_called_class());
    }
}
