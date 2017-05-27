<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserConversations]].
 *
 * @see UserConversations
 */
class UserConversationsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return UserConversations[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserConversations|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
