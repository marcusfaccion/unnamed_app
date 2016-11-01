<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\alerts\models\Alerts;

/**
 * AlertsSearch represents the model behind the search form of `app\modules\alerts\models\Alerts`.
 */
class AlertsSearch extends Alerts
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type_id', 'user_id', 'likes', 'unlikes'], 'integer'],
            [['title', 'description', 'created_date', 'updated_date', 'geom'], 'safe'],
            [['visible'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Alerts::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'type_id' => $this->type_id,
            'user_id' => $this->user_id,
            'created_date' => $this->created_date,
            'likes' => $this->likes,
            'unlikes' => $this->unlikes,
            'updated_date' => $this->updated_date,
            'visible' => $this->visible,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'geom', $this->geom]);

        return $dataProvider;
    }
}
