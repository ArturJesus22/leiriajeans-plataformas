<?php

namespace frontend\Models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\Models\Produto;

/**
 * ProdutoSearch represents the model behind the search form of `common\Models\Produto`.
 */
class ProdutoSearch extends Produto
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'cor_id', 'iva_id'], 'integer'],
            [['nome', 'descricao', 'sexo', 'tamanho_id'], 'safe'],
            [['preco'], 'number'],
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
        $query = Produto::find();

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
            'preco' => $this->preco,
            'cor_id' => $this->cor_id,
            'iva_id' => $this->iva_id,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'descricao', $this->descricao])
            ->andFilterWhere(['categoria' => $this->categoria])

            ->andFilterWhere(['like', 'tamanho_id', $this->tamanho_id]);


        $query->andFilterWhere(['like', 'sexo', $this->sexo]);


        return $dataProvider;
    }
}
