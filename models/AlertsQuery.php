<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Alerts]].
 *
 * @see Alerts
 */
class AlertsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Alerts[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Alerts|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    
    }    
    
    public function active($enable=1)
    {
        return $this->andOnCondition(['enable' => $enable]);
    }
    
    public function nonexistent()
    {
        return $this->join('left join', UserAlertNonexistence::tableName().' uan', 'id=uan.alert_id')
                ->where('uan.alert_id is not null');
    }
    
}
