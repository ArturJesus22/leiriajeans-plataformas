<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Carrinho extends ActiveRecord
{
    public static function tableName()
    {
        return 'carrinho';
    }

    public function rules()
    {
        return [
            [['userdata_id', 'total', 'ivatotal'], 'required'],
            [['userdata_id'], 'integer'],
            [['ivatotal', 'total'], 'number'],
            [['userdata_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserForm::class, 'targetAttribute' => ['userdata_id' => 'id']],
        ];
    }

    public function getProduto()
    {
        return $this->hasOne(Produto::class, ['id' => 'produto_id']);
    }

    public function getUserdata()
    {
        return $this->hasOne(UserForm::class, ['id' => 'userdata_id']);
    }

    public function getLinhasCarrinho()
    {
        return $this->hasMany(LinhaCarrinho::class, ['carrinho_id' => 'id']);
    }


    public function getCarrinhoAtual()
    {
        if (Yii::$app->user->isGuest) {
            return null;
        }

        $userForm = UserForm::findOne(['user_id' => Yii::$app->user->id]);
        if (!$userForm) {
            return null;
        }

        $carrinho = Carrinho::findOne(['userdata_id' => $userForm->id]);
        if (!$carrinho) {
            return null;
        }

        $linhasCarrinho = LinhaCarrinho::find()
            ->where(['carrinho_id' => $carrinho->id])
            ->all();

        $itens = [];
        foreach ($linhasCarrinho as $linha) {
            $produto = $linha->produto;
            if ($produto) {
                $itens[] = [
                    'id' => $produto->id,
                    'nome' => $produto->nome,
                    'preco' => $linha->precoVenda,
                ];
            }
        }

        return [
            'itens' => $itens,
            'total' => $carrinho->total,
            'ivatotal' => $carrinho->ivatotal
        ];
    }
}
