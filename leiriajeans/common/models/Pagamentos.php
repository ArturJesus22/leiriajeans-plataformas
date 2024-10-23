<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pagamento".
 *
 * @property int $id
 * @property int $fatura_id
 * @property int $metodopagamento_id
 * @property float|null $valor
 * @property string|null $data
 *
 * @property Fatura $fatura
 * @property Fatura[] $faturas
 * @property Metodopagamento $metodopagamento
 */
class Pagamentos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pagamento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fatura_id', 'metodopagamento_id'], 'required'],
            [['fatura_id', 'metodopagamento_id'], 'integer'],
            [['valor'], 'number'],
            [['data'], 'safe'],
            [['fatura_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fatura::class, 'targetAttribute' => ['fatura_id' => 'id']],
            [['metodopagamento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Metodopagamento::class, 'targetAttribute' => ['metodopagamento_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fatura_id' => 'Fatura ID',
            'metodopagamento_id' => 'Metodopagamento ID',
            'valor' => 'Valor',
            'data' => 'Data',
        ];
    }

    /**
     * Gets query for [[Fatura]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFatura()
    {
        return $this->hasOne(Fatura::class, ['id' => 'fatura_id']);
    }

    /**
     * Gets query for [[Faturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFaturas()
    {
        return $this->hasMany(Fatura::class, ['pagamento_id' => 'id']);
    }

    /**
     * Gets query for [[Metodopagamento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMetodopagamento()
    {
        return $this->hasOne(Metodopagamento::class, ['id' => 'metodopagamento_id']);
    }
}
