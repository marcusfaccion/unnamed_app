<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserNavigationRoutes]].
 *
 * @see UserNavigationRoutes
 */
class UserNavigationRoutesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return UserNavigationRoutes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserNavigationRoutes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
