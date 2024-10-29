<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "fatura".
 *
 * @property int $id
 * @property int $metodopagamento_id
 * @property int $metodoexpedicao_id
 * @property string|null $data
 * @property float|null $valorTotal
 * @property int|null $statuspedido
 *
 * @property LinhasFaturas[] $linhafaturas
 * @property MetodosExpedicoes $metodoexpedicao
 * @property MetodosPagamentos $metodopagamento
 */
class Faturas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fatura';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['metodopagamento_id', 'metodoexpedicao_id'], 'required'],
            [['metodopagamento_id', 'metodoexpedicao_id', 'statuspedido'], 'integer'],
            [['data'], 'safe'],
            [['valorTotal'], 'number'],
            [['metodoexpedicao_id'], 'exist', 'skipOnError' => true, 'targetClass' => MetodosExpedicoes::class, 'targetAttribute' => ['metodoexpedicao_id' => 'id']],
            [['metodopagamento_id'], 'exist', 'skipOnError' => true, 'targetClass' => MetodosPagamentos::class, 'targetAttribute' => ['metodopagamento_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'metodopagamento_id' => 'Metodopagamento ID',
            'metodoexpedicao_id' => 'Metodoexpedicao ID',
            'data' => 'Data',
            'valorTotal' => 'Valor Total',
            'statuspedido' => 'Statuspedido',
        ];
    }

    /**
     * Gets query for [[Linhafaturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhafaturas()
    {
        return $this->hasMany(LinhasFaturas::class, ['fatura_id' => 'id']);
    }

    /**
     * Gets query for [[Metodoexpedicao]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMetodoexpedicao()
    {
        return $this->hasOne(MetodosExpedicoes::class, ['id' => 'metodoexpedicao_id']);
    }

    /**
     * Gets query for [[Metodopagamento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMetodopagamento()
    {
        return $this->hasOne(MetodosPagamentos::class, ['id' => 'metodopagamento_id']);
    }
}
