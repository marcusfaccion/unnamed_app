<?php

namespace app\modules\alerts\models;

/**
 * This is the ActiveQuery class for [[AlertTypesSpatialTypes]].
 *
 * @see AlertTypesSpatialTypes
 */
class AlertTypesSpatialTypesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return AlertTypesSpatialTypes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AlertTypesSpatialTypes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
