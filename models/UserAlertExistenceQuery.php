<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserAlertExistence]].
 *
 * @see UserAlertExistence
 */
class UserAlertExistenceQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return UserAlertExistence[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserAlertExistence|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
