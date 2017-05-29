<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_conversations".
 *
 * @property integer $user_id
 * @property integer $user_id2
 * @property string $created_date
 * @property string $text
 * @property integer $id
 */
class UserConversations extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    //const SCENARIO_UPDATE = 'update';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_conversations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'user_id2', 'text'], 'required'],
            [['user_id', 'user_id2', 'user_saw', 'user2_saw', 'id'], 'integer'],
            [['created_date'], 'safe'],
            [['text'], 'string'],
        ];
    }
    
     // define os atributos seguros "safe" para massive atribuition via $model->attributes 
    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['text', 'created_date', 'user_id', 'user_id2'],
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
   public function getUser2()
   {
       return $this->hasOne(Users::className(), ['id' => 'user_id2']);
   }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'user_id2' => 'User Id2',
            'created_date' => 'Created Date',
            'text' => 'Text',
            'id' => 'ID',
        ];
    }

    /**
     * @inheritdoc
     * @return UserConversationsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserConversationsQuery(get_called_class());
    }
}
