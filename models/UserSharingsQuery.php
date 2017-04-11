<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserSharings]].
 *
 * @see UserSharings
 */
class UserSharingsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return UserSharings[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserSharings|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
