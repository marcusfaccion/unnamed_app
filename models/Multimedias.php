<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "multimedias".
 *
 * @property integer $id
 * @property integer $type_id
 * @property string $title
 * @property string $created_date
 * @property string $src
 *
 * @property MultimediaTypes $type
 */
class Multimedias extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'multimedias';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id'], 'integer'],
            [['created_date'], 'safe'],
            [['title'], 'string', 'max' => 40],
            [['src'], 'string', 'max' => 512],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => MultimediaTypes::className(), 'targetAttribute' => ['type_id' => 'id']],
            [['gallery_id'], 'exist', 'skipOnError' => true, 'targetClass' => Galleries::className(), 'targetAttribute' => ['gallery_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Type ID',
            'gallery_id' => 'Galeria',
            'title' => 'Title',
            'created_date' => 'Created Date',
            'src' => 'Src',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(MultimediaTypes::className(), ['id' => 'type_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGallery()
    {
        return $this->hasOne(Galleries::className(), ['id' => 'gallery_id']);
    }
}
