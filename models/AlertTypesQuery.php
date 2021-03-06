<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[AlertTypes]].
 *
 * @see AlertTypes
 */
class AlertTypesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return AlertTypes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AlertTypes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
