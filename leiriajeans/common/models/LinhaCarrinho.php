<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "linhacarrinho".
 *
 * @property int $id
 * @property int $carrinho_id
 * @property int $produto_id
 * @property int $quantidade
 * @property float $precoVenda
 * @property float $subTotal
 * @property float $valorIva
 *
 * @property Produto $produto
 * @property Carrinho $carrinho
 */
class LinhaCarrinho extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'linhacarrinho';
    }

    public function rules()
    {
        return [
            [['carrinho_id', 'produto_id', 'quantidade'], 'required'],
            [['carrinho_id', 'produto_id', 'quantidade'], 'integer'],
            [['precoVenda', 'subTotal', 'valorIva'], 'number'],
        ];
    }

    public function getProduto()
    {
        return $this->hasOne(Produto::class, ['id' => 'produto_id']);
    }

    public function getCarrinho()
    {
        return $this->hasOne(Carrinho::class, ['id' => 'carrinho_id']);
    }
}
