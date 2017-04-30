<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_alert_existence".
 *
 * @property integer $user_id
 * @property integer $alert_id
 * @property string $created_date
 */
class UserAlertExistence extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_alert_existence';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'alert_id'], 'required'],
            [['user_id', 'alert_id'], 'integer'],
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
            'alert_id' => 'Alert ID',
            'created_date' => 'Created Date',
        ];
    }

    /**
     * @inheritdoc
     * @return UserAlertExistenceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserAlertExistenceQuery(get_called_class());
    }
}
