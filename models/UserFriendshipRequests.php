<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_friendship_requests".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $requested_user_id
 * @property string $created_date
 * @property integer $enable
 */
class UserFriendshipRequests extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_friendship_requests';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'requested_user_id', 'enable'], 'integer'],
            [['created_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'requested_user_id' => 'Requested User ID',
            'created_date' => 'Created Date',
            'enable' => 'Enable',
        ];
    }
    
    // define os atributos seguros "safe" para massive atribuition via $model->attributes 
    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['user_id', 'requested_user_id', 'enable', 'created_date'],
        ];
    }
    
    /**
     * @inheritdoc
     * @return UserFriendshipRequestsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserFriendshipRequestsQuery(get_called_class());
    }
    
    /**
     * Retorna usuário de quem se solicita a amizade
     * @return \yii\db\ActiveQuery
     */
    public function getResponser()
    {
        return $this->hasOne(Users::className(), ['id' => 'requested_user_id']);
    }
    
    /**
     * Retorna usuário que solicita amizade
     * @return \yii\db\ActiveQuery
     */
    public function getRequester()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
}
