<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bike_keeper_comments".
 *
 * @property integer $id
 * @property integer $bike_keeper_id
 * @property integer $user_id
 * @property string $text
 * @property string $created_date
 */
class BikeKeeperComments extends \yii\db\ActiveRecord
{
	
	const SCENARIO_CREATE = 'create';

	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bike_keeper_comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bike_keeper_id', 'user_id'], 'integer'],
            [['text'], 'string'],
            [['created_date'], 'safe'],
        ];
    }

	// define os atributos seguros "safe" para massive atribuition via $model->attributes 
    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['text', 'user_id', 'bike_keeper_id'],
        ];
    }
	
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bike_keeper_id' => 'BikeKeeper ID',
            'user_id' => 'User ID',
            'text' => 'Text',
            'created_date' => 'Created Date',
        ];
    }

    /**
     * @inheritdoc
     * @return BikeKeeperCommentsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BikeKeeperCommentsQuery(get_called_class());
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
   public function getBikeKeeper()
   {
       return $this->hasOne(BikeKeepers::className(), ['id' => 'bike_keeper_id']);
   }
}
