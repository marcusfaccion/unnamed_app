<?php

namespace app\modules\alerts\models;

/**
 * This is the ActiveQuery class for [[SpatialTypes]].
 *
 * @see SpatialTypes
 */
class SpatialTypesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SpatialTypes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SpatialTypes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
