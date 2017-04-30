<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserAlertRates]].
 *
 * @see UserAlertRates
 */
class UserAlertRatesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return UserAlertRates[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserAlertRates|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
