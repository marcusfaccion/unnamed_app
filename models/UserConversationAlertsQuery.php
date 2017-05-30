<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserConversationAlerts]].
 *
 * @see UserConversationAlerts
 */
class UserConversationAlertsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return UserConversationAlerts[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserConversationAlerts|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
