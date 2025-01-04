<?php

namespace frontend\controllers;

use common\models\Fatura;
use common\models\LinhaCarrinho;
use common\models\Linhafatura;
use common\models\MetodoExpedicao;
use common\models\MetodoPagamento;
use common\models\User;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use common\models\Produto;
use common\models\Carrinho;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\models\UserForm;

/**
 * CarrinhosController implements the CRUD actions for Carrinho model.
 */
class CarrinhosController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $userForm = UserForm::findOne(['user_id' => Yii::$app->user->id]);
        if (!$userForm) {
            return $this->redirect(['site/index']);
        }

        $carrinho = Carrinho::findOne(['userdata_id' => $userForm->id]);
        if (!$carrinho) {
            return $this->render('index', [
                'carrinhoAtual' => null
            ]);
        }

        $linhasCarrinho = LinhaCarrinho::find()
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
            'carrinhoAtual' => $carrinhoAtual,
            'linhasCarrinho' => $linhasCarrinho // Passa as linhas do carrinho para a view
        ]);
    }

    public function beforeAction($action)
    {
        if ($action->id === 'add') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionAdd($produtos_id)
    {
        $produto = Produto::findOne($produtos_id);

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $userForm = UserForm::findOne(['user_id' => Yii::$app->user->id]);
        if (!$userForm) {
            return $this->redirect(['site/index']);
        }

        $carrinho = Carrinho::findOne(['userdata_id' => $userForm->id]) ?? new Carrinho([
            'produto_id' => $produto->id,
            'userdata_id' => $userForm->id,
            'total' => 0,
            'ivatotal' => 0,
        ]);

        if ($carrinho->isNewRecord && !$carrinho->save()) {
            return $this->redirect(['index']); // Redireciona se houver erro ao criar o carrinho
        }

        $id = Yii::$app->request->post('id');


        if (!$produto) {
            return $this->redirect(['index']); // Redireciona se o produto não for encontrado
        }

        // Verifica se já existe uma linha para este produto
        $linhaCarrinho = LinhaCarrinho::find()
            ->where(['carrinho_id' => $carrinho->id, 'produto_id' => $produtos_id]) // Verifique apenas linhas ativas
            ->one();

        if ($linhaCarrinho) {
            // Se já existe, atualiza a quantidade e totais
            $linhaCarrinho->quantidade++;
            $linhaCarrinho->precoVenda = $produto->preco;
            $linhaCarrinho->subTotal = $produto->preco * $linhaCarrinho->quantidade;
            $linhaCarrinho->valorIva = $linhaCarrinho->subTotal * ($produto->iva->percentagem / 100);
        } else {
            // Se não existe, cria nova linha
            $linhaCarrinho = new LinhaCarrinho([
                'carrinho_id' => $carrinho->id,
                'produto_id' => $produtos_id,
                'quantidade' => 1,
                'precoVenda' => $produto->preco,
                'subTotal' => $produto->preco,
                'valorIva' => $produto->preco * ($produto->iva->percentagem / 100),
            ]);
        }

        if (!$linhaCarrinho->save()) {
            return $this->redirect(['index']); // Redireciona se houver erro ao salvar a linha do carrinho
        }

        // Atualiza os totais do carrinho
        $this->atualizarTotaisCarrinho($carrinho);

        return $this->redirect(['index']); // Redireciona para a página de produtos
    }

    public function actionRemove($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $userForm = UserForm::findOne(['user_id' => Yii::$app->user->id]);
        if (!$userForm) {
            return $this->redirect(['index']);
        }

        $carrinho = Carrinho::findOne(['userdata_id' => $userForm->id]);
        if ($carrinho) {
            // Remove a linha do carrinho
            LinhaCarrinho::deleteAll(['carrinho_id' => $carrinho->id, 'produto_id' => $id]);

            // Verifica se ainda existem produtos no carrinho
            $temProdutos = LinhaCarrinho::find()->where(['carrinho_id' => $carrinho->id])->exists();

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

        $userForm = UserForm::findOne(['user_id' => Yii::$app->user->id]);
        if (!$userForm) {
            return $this->redirect(['site/index']);
        }

        $carrinho = Carrinho::findOne(['userdata_id' => $userForm->id]);
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
        $linhaCarrinho = LinhaCarrinho::findOne(['carrinho_id' => $carrinho->id, 'produto_id' => $id]);
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
        $carrinho->total = LinhaCarrinho::find()
            ->where(['carrinho_id' => $carrinho->id])
            ->sum('subTotal') ?? 0;

        $carrinho->ivatotal = LinhaCarrinho::find()
            ->where(['carrinho_id' => $carrinho->id])
            ->sum('valorIva') ?? 0;

        $carrinho->save();
    }

    public function actionAddToCart()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $userForm = UserForm::findOne(['user_id' => Yii::$app->user->id]);
        if (!$userForm) {
            return $this->redirect(['site/index']);
        }

        $id = Yii::$app->request->post('id');
        $produto = Produto::findOne($id);

        if (!$produto) {
            return $this->redirect(['index']); // Redireciona se o produto não for encontrado
        }

        // Procura um carrinho existente ou cria um novo
        $carrinho = Carrinho::findOne(['userdata_id' => $userForm->id]) ?? new Carrinho([
            'userdata_id' => $userForm->id,
            'total' => 0,
            'ivatotal' => 0,
        ]);

        if ($carrinho->isNewRecord && !$carrinho->save()) {
            return $this->redirect(['index']); // Redireciona se houver erro ao criar o carrinho
        }

        // Verifica se já existe uma linha para este produto
        $linhaCarrinho = LinhaCarrinho::findOne([
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
            $linhaCarrinho = new LinhaCarrinho([
                'carrinho_id' => $carrinho->id,
                'produto_id' => $id,
                'quantidade' => 1,
                'precoVenda' => $produto->preco,
                'subTotal' => $produto->preco,
                'valorIva' => $produto->preco * ($produto->iva->percentagem / 100),
            ]);
        }

        if (!$linhaCarrinho->save()) {
            return $this->redirect(['index']); // Redireciona se houver erro ao salvar a linha do carrinho
        }

        // Atualiza os totais do carrinho
        $this->atualizarTotaisCarrinho($carrinho);

        return $this->redirect(['index']); // Redireciona para a página de produtos
    }

    public function actionView($id)
    {
        // Encontra a fatura com o ID fornecido
        $model = $this->findModel($id);

        // Busca as linhas da fatura associadas
        $linhasFatura = $model->getLinhafaturas()->all();

        return $this->render('view', [
            'model' => $model,
            'linhasFatura' => $linhasFatura, // Passa as linhas da fatura para a view
        ]);
    }

    public function actionCheckout()
    {
        // Obter o ID do usuário logado
        $userId = Yii::$app->user->id;

        // Buscar o UserForm relacionado ao usuário logado
        $userdata = UserForm::findOne(['user_id' => $userId]);
        if ($userdata === null) {
            throw new NotFoundHttpException('UserData não encontrado para o usuário.');
        }

        // Obter o userdata_id a partir do UserForm
        $userdataId = $userdata->id;

        // Buscar o carrinho associado ao userdata_id
        $carrinho = Carrinho::findOne(['userdata_id' => $userdataId]);

        // Se não encontrou o carrinho, lançar erro
        if ($carrinho === null) {
            throw new NotFoundHttpException('Carrinho não encontrado para o usuário.');
        }


        // Buscar as linhas do carrinho usando apenas o carrinho_id
        $linhasCarrinho = LinhaCarrinho::find()->where(['carrinho_id' => $carrinho->id])->all();



        // Buscar métodos de pagamento e expedição
        $metodosPagamento = MetodoPagamento::find()->all();
        $metodosExpedicao = MetodoExpedicao::find()->all();

        // Renderizar a view
        return $this->render('checkout', [
            'linhasCarrinho' => $linhasCarrinho,
            'metodosPagamento' => $metodosPagamento,
            'metodosExpedicao' => $metodosExpedicao,
        ]);
    }




}