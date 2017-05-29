<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "online_users".
 *
 * @property integer $user_id
 * @property string $updated_date
 *
 * @property Users $user
 */
class OnlineUsers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'online_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'updated_date'], 'required'],
            [['user_id'], 'integer'],
            [['updated_date'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'updated_date' => 'Updated Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return OnlineUsersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OnlineUsersQuery(get_called_class());
    }
}
