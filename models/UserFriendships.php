<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_friendships".
 * @property string $created_date
 * @property integer $user_id
 * @property integer $friend_user_id
 */
class UserFriendships extends \yii\db\ActiveRecord
{
    
    const SCENARIO_CREATE = 'create';
    
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
            [['created_date'], 'safe']
        ];
    }
    
    // define os atributos seguros "safe" para massive atribuition via $model->attributes 
    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['user_id', 'friend_user_id', 'created_date'],
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
            'created_date' => 'Created Date',
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
