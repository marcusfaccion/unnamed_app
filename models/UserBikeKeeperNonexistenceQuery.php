<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserBikeKeeperExistence]].
 *
 * @see UserBikeKeeperExistence
 */
class UserBikeKeeperNonexistenceQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return UserBikeKeeperExistence[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserBikeKeeperExistence|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
