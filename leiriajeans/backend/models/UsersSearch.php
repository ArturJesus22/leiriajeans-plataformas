<?php

namespace backend\models;

use common\models\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UsersForm;
use yii\helpers\VarDumper;

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
            ->joinWith('authAssignment') // Junta com auth_assignment usando a relação definida
            ->where(['auth_assignment.item_name' => 'cliente']); // Filtra para usuários com papel "cliente"

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1'); // Retorna zero registros se a validação falhar
            return $dataProvider;
        }

        // Filtros adicionais
        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'nif', $this->nif])
            ->andFilterWhere(['like', 'localidade', $this->localidade]);

        return $dataProvider;
    }

}
