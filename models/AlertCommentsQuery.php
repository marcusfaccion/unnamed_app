<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[AlertComments]].
 *
 * @see AlertComments
 */
class AlertCommentsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return AlertComments[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AlertComments|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
