<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_alert_rates".
 *
 * @property integer $user_id
 * @property integer $alert_id
 * @property string $created_date
 * @property string $updated_date
 * @property string $rating
 */
class UserAlertRates extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_alert_rates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'alert_id'], 'required'],
            [['user_id', 'alert_id'], 'integer'],
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
            'alert_id' => 'Alert ID',
            'created_date' => 'Created Date',
            'updated_date' => 'Updated Date',
            'rating' => 'Rating',
        ];
    }

    /**
     * @inheritdoc
     * @return UserAlertRatesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserAlertRatesQuery(get_called_class());
    }
}
