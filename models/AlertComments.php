<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "alert_comments".
 *
 * @property integer $id
 * @property integer $alert_id
 * @property integer $user_id
 * @property string $text
 * @property string $created_date
 */
class AlertComments extends \yii\db\ActiveRecord
{
	
	const SCENARIO_CREATE = 'create';

	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alert_comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alert_id', 'user_id'], 'integer'],
            [['text'], 'string'],
            [['created_date'], 'safe'],
        ];
    }

	// define os atributos seguros "safe" para massive atribuition via $model->attributes 
    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['text', 'user_id', 'alert_id'],
        ];
    }
	
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alert_id' => 'Alert ID',
            'user_id' => 'User ID',
            'text' => 'Text',
            'created_date' => 'Created Date',
        ];
    }

    /**
     * @inheritdoc
     * @return AlertCommentsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AlertCommentsQuery(get_called_class());
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
   public function getAlert()
   {
       return $this->hasOne(Alerts::className(), ['id' => 'alert_id']);
   }
}
