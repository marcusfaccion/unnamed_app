<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[OnlineUsers]].
 *
 * @see OnlineUsers
 */
class OnlineUsersQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return OnlineUsers[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return OnlineUsers|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
