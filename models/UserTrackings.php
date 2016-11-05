<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_trackings".
 *
 * @property integer $id
 * @property string $latlng
 * @property integer $user_id
 * @property string $register_date
 *
 * @property Users $users
 */
class UserTrackings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_trackings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['register_date'], 'safe'],
            [['geom'], 'string'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'latlng' => 'Latlng',
            'user_id' => 'Users ID',
            'geom' => 'Posição',
            'register_date' => 'Register Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
}
