<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_friendships".
 *
 * @property integer $user_id
 * @property integer $friend_user_id
 */
class UserFriendships extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_friendships';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'friend_user_id'], 'required'],
            [['user_id', 'friend_user_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'friend_user_id' => 'Friend User ID',
        ];
    }

    /**
     * @inheritdoc
     * @return UserFriendshipsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserFriendshipsQuery(get_called_class());
    }
}
