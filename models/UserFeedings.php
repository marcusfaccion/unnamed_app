<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_feedings".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $user_sharing_id
 * @property string $text
 * @property integer $likes
 * @property integer $dislikes
 * @property string $created_date
 * @property string $updated_date
 * @property integer $view_level_id
 *
 * @property UserSharings $userSharing
 * @property Users $user
 * @property ViewLevels $viewLevel
 * @property UserSharings[] $userSharings
 */
class UserFeedings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_feedings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'user_sharing_id', 'likes', 'dislikes', 'view_level_id'], 'integer'],
            [['text'], 'string'],
            [['created_date', 'updated_date'], 'safe'],
            [['user_sharing_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserSharings::className(), 'targetAttribute' => ['user_sharing_id' => 'id']],
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
            'user_sharing_id' => 'User Sharing ID',
            'text' => 'Text',
            'likes' => 'Likes',
            'dislikes' => 'Unlikes',
            'created_date' => 'Created Date',
            'updated_date' => 'Updated Date',
            'view_level_id' => 'View Level ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSharing()
    {
        return $this->hasOne(UserSharings::className(), ['id' => 'user_sharing_id']);
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
     * @return \yii\db\ActiveQuery
     */
    public function getUserSharings()
    {
        return $this->hasMany(UserSharings::className(), ['user_feeding_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return UserFeedingsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserFeedingsQuery(get_called_class());
    }
}
