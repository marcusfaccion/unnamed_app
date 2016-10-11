<?php

namespace app\modules\alerts\models;

use Yii;

/**
 * This is the model class for table "alerts".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $type_id
 * @property integer $user_id
 * @property string $created_date
 * @property integer $likes
 * @property integer $unlikes
 * @property integer $geometry_id
 * @property string $updated_date
 * @property boolean $visible
 *
 * @property Geometries $geometry
 */
class Alerts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alerts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['type_id', 'user_id', 'likes', 'unlikes', 'geometry_id'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            [['visible'], 'boolean'],
            [['title'], 'string', 'max' => 40],
            [['geometry_id'], 'exist', 'skipOnError' => true, 'targetClass' => Geometries::className(), 'targetAttribute' => ['geometry_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'type_id' => Yii::t('app', 'Type ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_date' => Yii::t('app', 'Created Date'),
            'likes' => Yii::t('app', 'Likes'),
            'unlikes' => Yii::t('app', 'Unlikes'),
            'geometry_id' => Yii::t('app', 'Geometry ID'),
            'updated_date' => Yii::t('app', 'Updated Date'),
            'visible' => Yii::t('app', 'Visible'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeometry()
    {
        return $this->hasOne(Geometries::className(), ['id' => 'geometry_id']);
    }

    /**
     * @inheritdoc
     * @return AlertsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AlertsQuery(get_called_class());
    }
}
