<?php

namespace app\modules\alerts\models;

/**
 * This is the ActiveQuery class for [[AlertsTypes]].
 *
 * @see AlertsTypes
 */
class AlertsTypesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return AlertsTypes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AlertsTypes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
