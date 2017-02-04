<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[BikeKeepers]].
 *
 * @see BikeKeepers
 */
class BikeKeepersQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return BikeKeepers[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return BikeKeepers|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    /**
     * @inheritdoc
     * @return BikeKeepers|array|null
     */
}
