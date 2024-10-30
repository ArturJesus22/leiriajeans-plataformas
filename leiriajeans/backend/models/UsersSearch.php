<?php

namespace backend\models;

use common\models\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UsersForm;

/**
 * UsersSearch represents the model behind the search form of `common\models\UsersForm`.
 */
class UsersSearch extends UsersForm
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['nome', 'codpostal', 'localidade', 'rua', 'nif', 'telefone'], 'safe'],
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
        $query = UsersForm::find()
            ->joinWith('authAssignment') // junta a tabela auth_assignment(join)
            ->where(['auth_assignment.item_name' => 'cliente']); // Filtra pela role "cliente"

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'nome', $this->nome]);
        $query->andFilterWhere(['like', 'nif', $this->nif]);
        $query->andFilterWhere(['like', 'localidade', $this->localidade]);

        return $dataProvider;
    }

}
