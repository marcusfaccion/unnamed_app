<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_sharing_types".
 *
 * @property integer $id
 * @property string $name
 *
 * @property UserSharings[] $userSharings
 */
class UserSharingTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_sharing_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSharings()
    {
        return $this->hasMany(UserSharings::className(), ['sharing_type_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return UserSharingTypesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserSharingTypesQuery(get_called_class());
    }
}
