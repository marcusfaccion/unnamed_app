<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserBikeKeeperRates]].
 *
 * @see UserBikeKeeperRates
 */
class UserBikeKeeperRatesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return UserBikeKeeperRates[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserBikeKeeperRates|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
