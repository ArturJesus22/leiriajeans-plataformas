<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "produto".
 *
 * @property int $id
 * @property string|null $nome
 * @property string|null $descricao
 * @property float|null $preco
 * @property int|null $stock
 * @property string|null $tamanho_id
 * @property int|null $cor_id
 * @property int|null $iva_id
 * @property int|null $categoria_id
 *
 * @property Carrinho[] $carrinho
 * @property Categoria $categoria
 * @property Cor $cor
 * @property Imagem[] $imagems
 * @property Iva $iva
 * @property LinhaCarrinho[] $linhacarrinho
 */
class Produto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'produto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descricao'], 'string'],
            [['preco'], 'number'],
            [['stock', 'cor_id', 'iva_id', 'categoria_id'], 'integer'],
            [['nome'], 'string', 'max' => 255],
            [['tamanho_id'], 'string', 'max' => 5],
            [['categoria_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categoria::class, 'targetAttribute' => ['categoria_id' => 'id']],
            [['cor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cor::class, 'targetAttribute' => ['cor_id' => 'id']],
            [['iva_id'], 'exist', 'skipOnError' => true, 'targetClass' => Iva::class, 'targetAttribute' => ['iva_id' => 'id']],
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
            'preco' => 'Preco',
            'stock' => 'Stock',
            'tamanho_id' => 'Tamanho ID',
            'cor_id' => 'Cor ID',
            'iva_id' => 'Iva ID',
            'categoria_id' => 'Categoria ID',
        ];
    }

    /**
     * Gets query for [[Carrinho]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarrinhos()
    {
        return $this->hasMany(Carrinho::class, ['produto_id' => 'id']);
    }

    public function getTamanhos()
    {
        return $this->hasMany(Tamanho::class, ['id' => 'tamanho_id'])
            ->viaTable('produto_tamanho', ['produto_id' => 'id']);
    }


    /**
     * Gets query for [[Categoria]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(Categoria::class, ['id' => 'categoria_id']);
    }

    /**
     * Gets query for [[Cor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCor()
    {
        return $this->hasOne(Cor::class, ['id' => 'cor_id']);
    }

    /**
     * Gets query for [[Imagems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getImagens()
    {
        return $this->hasMany(Imagem::class, ['produto_id' => 'id']);
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
     * Gets query for [[Linhacarrinhos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhacarrinhos()
    {
        return $this->hasMany(LinhaCarrinho::class, ['produto_id' => 'id']);
    }
}