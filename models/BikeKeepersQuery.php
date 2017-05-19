<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[BikeKeepers]].
 *
 * @see BikeKeepers
 */
class BikeKeepersQuery extends \yii\db\ActiveQuery
{
    
    public function active($enable=1)
    {
        $teste2 = 5;
        return $this->andOnCondition(['enable' => $enable]);
    }

    /**
     * @inheritdoc
     * @return BikeKeepers[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    public function nonexistent()
    {
        return $this->join('left join', UserBikeKeeperNonexistence::tableName().' ubn', 'id=ubn.bike_keeper_id')
                ->where('ubn.bike_keeper_id is not null');
    }
    
    /**
     * @inheritdoc
     * @return BikeKeepers|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    /**
     * @inheritdoc
     * @return BikeKeepers|array|null
     */
}
