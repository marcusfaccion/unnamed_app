<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_levels".
 *
 * @property integer $id
 * @property string $name
 *
 * @property UserFeedings[] $userFeedings
 * @property UserSharings[] $userSharings
 */
class ViewLevels extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'view_levels';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 50],
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
    public function getUserFeedings()
    {
        return $this->hasMany(UserFeedings::className(), ['view_level_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSharings()
    {
        return $this->hasMany(UserSharings::className(), ['view_level_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return ViewLevelsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ViewLevelsQuery(get_called_class());
    }
}
