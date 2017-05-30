<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_conversation_alerts".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $user_id2
 * @property string $created_date
 *
 * @property Users $user
 * @property Users $userId2
 */
class UserConversationAlerts extends \yii\db\ActiveRecord
{
    
    const SCENARIO_CREATE = 'create';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_conversation_alerts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'user_id2'], 'integer'],
            [['created_date'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['user_id2'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id2' => 'id']],
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
            'user_id2' => 'User Id2',
            'created_date' => 'Created Date',
        ];
    }

      // define os atributos seguros "safe" para massive atribuition via $model->attributes 
    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['created_date', 'user_id2', 'user_id2'],
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
     * @return \yii\db\ActiveQuery
     */
    public function getUserId2()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id2']);
    }

    /**
     * @inheritdoc
     * @return UserConversationAlertsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserConversationAlertsQuery(get_called_class());
    }
}
