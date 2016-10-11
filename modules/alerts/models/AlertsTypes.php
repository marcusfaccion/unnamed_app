<?php

namespace app\modules\alerts\models;

use Yii;

/**
 * This is the model class for table "alerts_types".
 *
 * @property integer $id
 * @property string $desc
 * @property integer $parent_type_id
 *
 * @property AlertsTypes $parentType
 * @property AlertsTypes[] $alertsTypes
 */
class AlertsTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alerts_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_type_id'], 'integer'],
            [['desc'], 'string', 'max' => 50],
            [['parent_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => AlertsTypes::className(), 'targetAttribute' => ['parent_type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'desc' => Yii::t('app', 'Desc'),
            'parent_type_id' => Yii::t('app', 'Parent Type ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentType()
    {
        return $this->hasOne(AlertsTypes::className(), ['id' => 'parent_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlertsTypes()
    {
        return $this->hasMany(AlertsTypes::className(), ['parent_type_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return AlertsTypesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AlertsTypesQuery(get_called_class());
    }
}
