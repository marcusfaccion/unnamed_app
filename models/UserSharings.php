<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_sharings".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $user_feeding_id
 * @property integer $view_level_id
 * @property integer $content_id
 * @property string $created_date
 * @property string $updated_date
 * @property integer $sharing_type_id
 *
 * @property UserFeedings[] $userFeedings
 * @property UserFeedings $userFeeding
 * @property UserSharingTypes $sharingType
 * @property Users $user
 * @property ViewLevels $viewLevel
 */
class UserSharings extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_sharings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'user_feeding_id', 'view_level_id', 'content_id', 'sharing_type_id'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            [['user_feeding_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserFeedings::className(), 'targetAttribute' => ['user_feeding_id' => 'id']],
            [['sharing_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserSharingTypes::className(), 'targetAttribute' => ['sharing_type_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['view_level_id'], 'exist', 'skipOnError' => true, 'targetClass' => ViewLevels::className(), 'targetAttribute' => ['view_level_id' => 'id']],
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
            'user_feeding_id' => 'User Feeding ID',
            'view_level_id' => 'View Level ID',
            'content_id' => 'Content ID',
            'created_date' => 'Created Date',
            'updated_date' => 'Updated Date',
            'sharing_type_id' => 'Sharing Type ID',
        ];
    }

        // define os atributos seguros "safe" para população massiva via $model->attributes 
    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['user_id', 'user_feeding_id', 'sharing_type_id'],
            //self::SCENARIO_UPDATE => ['title', 'description', 'business_hours', 'used_capacity', 'is_open', 'cost', 'email', 'tel', 'user_id', 'public', 'address', 'outdoor'],
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserFeedings()
    {
        return $this->hasMany(UserFeedings::className(), ['user_sharing_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserFeeding()
    {
        return $this->hasOne(UserFeedings::className(), ['id' => 'user_feeding_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSharingType()
    {
        return $this->hasOne(UserSharingTypes::className(), ['id' => 'sharing_type_id']);
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
    public function getViewLevel()
    {
        return $this->hasOne(ViewLevels::className(), ['id' => 'view_level_id']);
    }

    /**
     * @inheritdoc
     * @return UserSharingsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserSharingsQuery(get_called_class());
    }
}
