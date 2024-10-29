<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "metodoexpedicao".
 *
 * @property int $id
 * @property string $nome
 * @property string|null $descricao
 * @property float $custo
 * @property int $prazo_entrega
 * @property int $ativo
 *
 * @property Faturas[] $faturas
 */
class MetodosExpedicoes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'metodoexpedicao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'prazo_entrega'], 'required'],
            [['descricao'], 'string'],
            [['custo'], 'number'],
            [['prazo_entrega', 'ativo'], 'integer'],
            [['nome'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'descricao' => 'Descricao',
            'custo' => 'Custo',
            'prazo_entrega' => 'Prazo Entrega',
            'ativo' => 'Ativo',
        ];
    }

    /**
     * Gets query for [[Faturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFaturas()
    {
        return $this->hasMany(Faturas::class, ['metodoexpedicao_id' => 'id']);
    }
}
