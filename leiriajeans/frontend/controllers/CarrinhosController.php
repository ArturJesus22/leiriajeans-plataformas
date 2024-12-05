<?php

namespace frontend\controllers;

use common\models\LinhasCarrinhos;
use Yii;
use yii\web\Response;
use common\models\Produtos;
use common\models\Carrinhos;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\models\UsersForm;

/**
 * CarrinhosController implements the CRUD actions for Carrinhos model.
 */
class CarrinhosController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'add' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if ($action->id === 'add') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionAdd()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        try {
            if (Yii::$app->user->isGuest) {
                return ['success' => false, 'message' => 'Faça login primeiro.'];
            }

            $userForm = UsersForm::findOne(['user_id' => Yii::$app->user->id]);
            if (!$userForm) {
                return ['success' => false, 'message' => 'Utilizador não encontrado.'];
            }

            $id = Yii::$app->request->post('id');
            $produto = Produtos::findOne($id);

            if (!$produto) {
                return ['success' => false, 'message' => 'Produto não encontrado.'];
            }

            // Procura ou cria um carrinho para o usuário
            $carrinho = Carrinhos::findOne(['userdata_id' => $userForm->id]);
            if (!$carrinho) {
                $carrinho = new Carrinhos([
                    'userdata_id' => $userForm->id,
                    'total' => 0,
                    'ivatotal' => 0,
                    'produto_id' => $id, // Necessário devido à regra 'required'
                ]);
                if (!$carrinho->save()) {
                    return ['success' => false, 'message' => 'Erro ao criar carrinho'];
                }
            }

            // Verifica se já existe uma linha para este produto
            $linhaCarrinho = LinhasCarrinhos::findOne([
                'carrinho_id' => $carrinho->id,
                'produto_id' => $id
            ]);

            if ($linhaCarrinho) {
                // Se já existe, atualiza a quantidade e totais
                $linhaCarrinho->quantidade++;
                $linhaCarrinho->precoVenda = $produto->preco;
                $linhaCarrinho->subTotal = $produto->preco * $linhaCarrinho->quantidade;
                $linhaCarrinho->valorIva = $linhaCarrinho->subTotal * ($produto->iva->percentagem / 100);
            } else {
                // Se não existe, cria nova linha
                $linhaCarrinho = new LinhasCarrinhos([
                    'carrinho_id' => $carrinho->id,
                    'produto_id' => $id,
                    'quantidade' => 1,
                    'precoVenda' => $produto->preco,
                    'subTotal' => $produto->preco,
                    'valorIva' => $produto->preco * ($produto->iva->percentagem / 100),
                ]);
            }

            if (!$linhaCarrinho->save()) {
                return ['success' => false, 'message' => 'Erro ao guardar linha do carrinho: ' . implode(', ', $linhaCarrinho->getErrorSummary(true))];
            }

            // Atualiza os totais do carrinho
            $carrinho->total = LinhasCarrinhos::find()
                ->where(['carrinho_id' => $carrinho->id])
                ->sum('subTotal');
            $carrinho->ivatotal = LinhasCarrinhos::find()
                ->where(['carrinho_id' => $carrinho->id])
                ->sum('valorIva');
            $carrinho->save();

            return ['success' => true, 'message' => 'Produto adicionado ao carrinho com sucesso!'];

        } catch (\Exception $e) {
            Yii::error('Erro ao adicionar ao carrinho: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Erro ao adicionar produto: ' . $e->getMessage()];
        }
    }

    public function actionRemove($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $userForm = UsersForm::findOne(['user_id' => Yii::$app->user->id]);
        if ($userForm) {
            $carrinho = Carrinhos::findOne(['userdata_id' => $userForm->id]);
            if ($carrinho) {
                // Remove a linha do carrinho
                $linha = LinhasCarrinhos::findOne([
                    'carrinho_id' => $carrinho->id,
                    'produto_id' => $id
                ]);
                
                if ($linha) {
                    $linha->delete();

                    // Atualiza os totais do carrinho
                    $carrinho->total = LinhasCarrinhos::find()
                        ->where(['carrinho_id' => $carrinho->id])
                        ->sum('subTotal');
                    $carrinho->ivatotal = LinhasCarrinhos::find()
                        ->where(['carrinho_id' => $carrinho->id])
                        ->sum('valorIva');
                    $carrinho->save();
                }
            }
        }

        return $this->redirect(['faturas/index']);
    }

    // Método para obter o carrinho atual do usuário
    public function getCarrinhoAtual()
    {
        if (Yii::$app->user->isGuest) {
            return null;
        }

        $userForm = UsersForm::findOne(['user_id' => Yii::$app->user->id]);
        if (!$userForm) {
            return null;
        }

        $carrinho = Carrinhos::findOne(['userdata_id' => $userForm->id]);
        if (!$carrinho) {
            return null;
        }

        $linhasCarrinho = LinhasCarrinhos::find()
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
                    'quantidade' => $linha->quantidade,
                    'subtotal' => $linha->subTotal,
                    'valorIva' => $linha->valorIva
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
