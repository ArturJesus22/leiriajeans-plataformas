<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Faturas;

/**
 * FaturasSearch represents the model behind the search form of `common\models\Faturas`.
 */
class FaturasSearch extends Faturas
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'pagamento_id', 'metodoexpedicao_id', 'statuspedido'], 'integer'],
            [['data'], 'safe'],
            [['valorTotal'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Faturas::find();

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
            'pagamento_id' => $this->pagamento_id,
            'metodoexpedicao_id' => $this->metodoexpedicao_id,
            'data' => $this->data,
            'valorTotal' => $this->valorTotal,
            'statuspedido' => $this->statuspedido,
        ]);

        return $dataProvider;
    }
}
