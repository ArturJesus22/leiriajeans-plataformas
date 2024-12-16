<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "linhafatura".
 *
 * @property int $id
 * @property int|null $fatura_id
 * @property int|null $iva_id
 * @property int|null $linhacarrinho_id
 * @property int|null $produto_id
 * @property float|null $preco
 *
 * @property Avaliacao[] $avaliacaos
 * @property Fatura $fatura
 * @property Iva $iva
 * @property Linhacarrinho $linhacarrinho
 * @property Produto $produto
 */
class LinhaFatura extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'linhafatura';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fatura_id', 'iva_id', 'linhacarrinho_id', 'produto_id'], 'integer'],
            [['preco'], 'number'],
            [['fatura_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fatura::class, 'targetAttribute' => ['fatura_id' => 'id']],
            [['iva_id'], 'exist', 'skipOnError' => true, 'targetClass' => Iva::class, 'targetAttribute' => ['iva_id' => 'id']],
            [['linhacarrinho_id'], 'exist', 'skipOnError' => true, 'targetClass' => Linhacarrinho::class, 'targetAttribute' => ['linhacarrinho_id' => 'id']],
            [['produto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Produto::class, 'targetAttribute' => ['produto_id' => 'id']],
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
            'iva_id' => 'Iva ID',
            'linhacarrinho_id' => 'Linhacarrinho ID',
            'produto_id' => 'Produto ID',
            'preco' => 'Preco',

        ];
    }

    /**
     * Gets query for [[Avaliacaos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAvaliacaos()
    {
        return $this->hasMany(Avaliacao::class, ['linhafatura_id' => 'id']);
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
     * Gets query for [[Iva]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIva()
    {
        return $this->hasOne(Iva::class, ['id' => 'iva_id']);
    }

    /**
     * Gets query for [[Linhacarrinho]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhacarrinho()
    {
        return $this->hasOne(Linhacarrinho::class, ['id' => 'linhacarrinho_id']);
    }

    /**
     * Gets query for [[Produto]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduto()
    {
        return $this->hasOne(Produto::class, ['id' => 'produto_id']);
    }
}
