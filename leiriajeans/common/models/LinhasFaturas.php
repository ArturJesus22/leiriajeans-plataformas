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
 * @property float|null $preco
 *
 * @property Avaliacoes[] $avaliacaos
 * @property Faturas $fatura
 * @property Ivas $iva
 * @property LinhasCarrinhos $linhacarrinho
 */
class LinhasFaturas extends \yii\db\ActiveRecord
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
            [['fatura_id', 'iva_id', 'linhacarrinho_id'], 'integer'],
            [['preco'], 'number'],
            [['fatura_id'], 'exist', 'skipOnError' => true, 'targetClass' => Faturas::class, 'targetAttribute' => ['fatura_id' => 'id']],
            [['iva_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ivas::class, 'targetAttribute' => ['iva_id' => 'id']],
            [['linhacarrinho_id'], 'exist', 'skipOnError' => true, 'targetClass' => LinhasCarrinhos::class, 'targetAttribute' => ['linhacarrinho_id' => 'id']],
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
        return $this->hasMany(Avaliacoes::class, ['linhafatura_id' => 'id']);
    }

    /**
     * Gets query for [[Fatura]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFatura()
    {
        return $this->hasOne(Faturas::class, ['id' => 'fatura_id']);
    }

    /**
     * Gets query for [[Iva]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIva()
    {
        return $this->hasOne(Ivas::class, ['id' => 'iva_id']);
    }

    /**
     * Gets query for [[Linhacarrinho]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhacarrinho()
    {
        return $this->hasOne(LinhasCarrinhos::class, ['id' => 'linhacarrinho_id']);
    }

    /**
     * Gets query for [[Produto]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduto()
    {
        return $this->linhacarrinho->produto;
    }
}
