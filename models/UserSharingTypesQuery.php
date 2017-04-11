<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserSharingTypes]].
 *
 * @see UserSharingTypes
 */
class UserSharingTypesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return UserSharingTypes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserSharingTypes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
