<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "multimedia_types".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 *
 * @property Multimedias[] $multimedias
 */
class MultimediaTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'multimedia_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['title'], 'string', 'max' => 40],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMultimedias()
    {
        return $this->hasMany(Multimedias::className(), ['type_id' => 'id']);
    }
}
