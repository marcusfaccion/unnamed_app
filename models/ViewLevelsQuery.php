<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ViewLevels]].
 *
 * @see ViewLevels
 */
class ViewLevelsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ViewLevels[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ViewLevels|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
