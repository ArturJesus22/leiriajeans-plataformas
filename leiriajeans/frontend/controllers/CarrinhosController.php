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

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $userForm = UsersForm::findOne(['user_id' => Yii::$app->user->id]);
        if (!$userForm) {
            return $this->redirect(['site/index']);
        }

        $carrinho = Carrinhos::findOne(['userdata_id' => $userForm->id]);
        if (!$carrinho) {
            return $this->render('index', [
                'carrinhoAtual' => null
            ]);
        }

        $linhasCarrinho = LinhasCarrinhos::find()
            ->where(['carrinho_id' => $carrinho->id])
            ->with(['produto']) // Carrega os produtos relacionados
            ->all();

        $itens = [];
        $total = 0;
        $ivatotal = 0;

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
                $total += $linha->subTotal;
                $ivatotal += $linha->valorIva;
            }
        }

        $carrinhoAtual = [
            'itens' => $itens,
            'total' => $total,
            'ivatotal' => $ivatotal
        ];

        return $this->render('index', [
            'carrinhoAtual' => $carrinhoAtual
        ]);
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

            // Procura um carrinho existente ou cria um novo
            $carrinho = Carrinhos::findOne(['userdata_id' => $userForm->id]) ?? new Carrinhos([
                'userdata_id' => $userForm->id,
                'total' => 0,
                'ivatotal' => 0,
            ]);

            if ($carrinho->isNewRecord && !$carrinho->save()) {
                return ['success' => false, 'message' => 'Erro ao criar carrinho'];
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
                return ['success' => false, 'message' => 'Erro ao guardar linha do carrinho'];
            }

            // Atualiza os totais do carrinho
            $this->atualizarTotaisCarrinho($carrinho);

            return ['success' => true, 'message' => 'Produto adicionado ao carrinho com sucesso!'];

        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Erro ao adicionar produto: ' . $e->getMessage()];
        }
    }

    public function actionRemove($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $userForm = UsersForm::findOne(['user_id' => Yii::$app->user->id]);
        if (!$userForm) {
            return $this->redirect(['index']);
        }

        $carrinho = Carrinhos::findOne(['userdata_id' => $userForm->id]);
        if ($carrinho) {
            // Remove a linha do carrinho
            LinhasCarrinhos::deleteAll(['carrinho_id' => $carrinho->id, 'produto_id' => $id]);
            
            // Verifica se ainda existem produtos no carrinho
            $temProdutos = LinhasCarrinhos::find()->where(['carrinho_id' => $carrinho->id])->exists();
            
            if ($temProdutos) {
                // Atualiza os totais se ainda houver produtos
                $this->atualizarTotaisCarrinho($carrinho);
            } else {
                // Remove o carrinho se estiver vazio
                $carrinho->delete();
            }
        }

        return $this->redirect(['index']);
    }

    public function actionUpdateQuantidade($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $userForm = UsersForm::findOne(['user_id' => Yii::$app->user->id]);
        if (!$userForm) {
            return $this->redirect(['site/index']);
        }

        $carrinho = Carrinhos::findOne(['userdata_id' => $userForm->id]);
        if (!$carrinho) {
            Yii::$app->session->setFlash('error', 'Carrinho não encontrado.');
            return $this->redirect(['index']);
        }

        // Obtém a nova quantidade do formulário
        $quantidade = Yii::$app->request->post('quantidade');

        // Verifica se a quantidade é válida
        if ($quantidade < 1) {
            Yii::$app->session->setFlash('error', 'A quantidade deve ser pelo menos 1.');
            return $this->redirect(['index']);
        }

        // Busca a linha do carrinho
        $linhaCarrinho = LinhasCarrinhos::findOne(['carrinho_id' => $carrinho->id, 'produto_id' => $id]);
        if ($linhaCarrinho) {
            // Atualiza a quantidade
            $linhaCarrinho->quantidade = $quantidade;

            // Atualiza os valores da linha
            $linhaCarrinho->subTotal = $linhaCarrinho->precoVenda * $linhaCarrinho->quantidade;
            $linhaCarrinho->valorIva = $linhaCarrinho->subTotal * ($linhaCarrinho->produto->iva->percentagem / 100);

            if ($linhaCarrinho->save()) {
                // Atualiza os totais do carrinho
                $this->atualizarTotaisCarrinho($carrinho);
                Yii::$app->session->setFlash('success', 'Quantidade atualizada com sucesso!');
            } else {
                Yii::$app->session->setFlash('error', 'Erro ao atualizar a quantidade.');
            }
        } else {
            Yii::$app->session->setFlash('error', 'Produto não encontrado no carrinho.');
        }

        return $this->redirect(['index']);
    }

    // Método auxiliar para atualizar totais
    private function atualizarTotaisCarrinho($carrinho)
    {
        $carrinho->total = LinhasCarrinhos::find()
            ->where(['carrinho_id' => $carrinho->id])
            ->sum('subTotal') ?? 0;
            
        $carrinho->ivatotal = LinhasCarrinhos::find()
            ->where(['carrinho_id' => $carrinho->id])
            ->sum('valorIva') ?? 0;
            
        $carrinho->save();
    }
}
