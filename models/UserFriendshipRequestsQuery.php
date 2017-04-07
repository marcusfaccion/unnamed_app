<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserFriendshipRequests]].
 *
 * @see UserFriendshipRequests
 */
class UserFriendshipRequestsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return UserFriendshipRequests[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserFriendshipRequests|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
