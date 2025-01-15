<?php

namespace frontend\controllers;

use common\models\Fatura;
use common\models\LinhaCarrinho;
use common\models\Linhafatura;
use common\models\MetodoExpedicao;
use common\models\MetodoPagamento;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
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
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'view', 'add', 'remove', 'updatequantidade', 'totaisCarrinho', 'checkout'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'add', 'remove', 'updatequantidade', 'totaisCarrinho', 'checkout'],
                        'roles' => ['admin', 'funcionario', 'cliente'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        //verifica se o utilizador esta "logado"
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        //vai buscar o user_id da tabela userform que esta com login feito e manda para o site/index
        $userForm = UserForm::findOne(['user_id' => Yii::$app->user->id]);
        if (!$userForm) {
            return $this->redirect(['site/index']);
        }

        //procura um carrinho associado ao registo do utilizador (UserForm)
        $carrinho = Carrinho::findOne(['userdata_id' => $userForm->id]);
        if (!$carrinho) {
            return $this->render('index', [
                'carrinhoAtual' => null
            ]);
        }

        //procura todas as linhas do carrinho (produtos)
        $linhasCarrinho = LinhaCarrinho::find()
            ->where(['carrinho_id' => $carrinho->id])
            ->with(['produto']) // Carrega os produtos relacionados
            ->all();

        //inicializa os arrays e variáveis para guardar os produtos e totais do carrinho
        $itens = [];
        $total = 0;
        $ivatotal = 0;

        foreach ($linhasCarrinho as $linha) {
            $produto = $linha->produto;
            if ($produto) {
                // Adiciona as informações de cada item do carrinho
                $itens[] = [
                    'id' => $produto->id,
                    'nome' => $produto->nome,
                    'preco' => $linha->precoVenda,
                    'quantidade' => $linha->quantidade,
                    'subtotal' => $linha->subTotal,
                    'valorIva' => $linha->valorIva,
                    'stock' => $produto->stock
                ];
                $total += $linha->subTotal;
                $ivatotal += $linha->valorIva;
            }
        }

        //cria um array $carrinhoAtual com os dados do carrinho
        $carrinhoAtual = [
            'itens' => $itens,
            'total' => $total,
            'ivatotal' => $ivatotal
        ];

        //dá render da view index e passa os seguintes dados:
        //carrinhoAtual: processa os dados do carrinho atual
        //linhasCarrinho: passa as linhas do carrinho para a view
        return $this->render('index', [
            'carrinhoAtual' => $carrinhoAtual,
            'linhasCarrinho' => $linhasCarrinho
        ]);
    }

    public function actionAdd($produtos_id)
    {
        //procura o produto pelo id fornecido
        $produto = Produto::findOne($produtos_id);

        //verifica se o utilizador está "logado"
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        //vai buscar o user_id da tabela userform que esta com login feito e manda para o site/index
        $userForm = UserForm::findOne(['user_id' => Yii::$app->user->id]);
        if (!$userForm) {
            return $this->redirect(['site/index']);
        }


        //primeiro, tenta encontrar um carrinho associado ao utilizador (userdata_id).
        //se não encontrar, cria um novo Carrinho com valores iniciais (total e ivatotal = 0).
        $carrinho = Carrinho::findOne(['userdata_id' => $userForm->id]) ?? new Carrinho([
            'userdata_id' => $userForm->id,
            'total' => 0,
            'ivatotal' => 0,
        ]);

        //caso seja preciso criar o carrinho (indicado por isNewRecord) e não seja possível guardar na base de dados, redireciona para a página principal do carrinho (index).
        if ($carrinho->isNewRecord && !$carrinho->save()) {
            return $this->redirect(['index']);
        }

        //confirma se o produto procurado no início realmente existe
        //caso contrário, redireciona para a página principal do carrinho (index)
        if (!$produto) {
            return $this->redirect(['index']);
        }

        //tenta procurar na base de dados uma linha do carrinho que já associe o carrinho atual (carrinho_id) ao produto selecionado (produto_id).
        $linhaCarrinho = LinhaCarrinho::find()
            ->where(['carrinho_id' => $carrinho->id, 'produto_id' => $produtos_id])
            ->one();

        //se a linha do carrinho já existir
        //incrementa a quantidade do produto no carrinho
        //atualiza os campos:
        //precoVenda: Preço do produto
        //subTotal: Preço total para essa linha (preço do produto * quantidade).
        //valorIva: IVA calculado como uma porcentagem do subtotal.
        if ($linhaCarrinho) {
            $linhaCarrinho->quantidade++;
            $linhaCarrinho->precoVenda = $produto->preco;
            $linhaCarrinho->subTotal = $produto->preco * $linhaCarrinho->quantidade;
            $linhaCarrinho->valorIva = $linhaCarrinho->subTotal * ($produto->iva->percentagem / 100);
        }
        //se nao exitir linha carrinho faz uma nova
        //quantidade inicial como 1.
        //precoVenda igual ao preço do produto.
        //subTotal e valorIva calculados com base no preço do produto e na taxa de IVA.
        else {
            $linhaCarrinho = new LinhaCarrinho([
                'carrinho_id' => $carrinho->id,
                'produto_id' => $produtos_id,
                'quantidade' => 1,
                'precoVenda' => $produto->preco,
                'subTotal' => $produto->preco,
                'valorIva' => $produto->preco * ($produto->iva->percentagem / 100),
            ]);
        }

        //guarda a linha carrinho
        if (!$linhaCarrinho->save()) {
            return $this->redirect(['index']);
        }

        //atualiza os valores totais do carrinho (a setinha para cima e para baixo)
        $this->atualizarTotaisCarrinho($carrinho);

        return $this->redirect(['index']);
    }

    public function actionRemove($id)
    {
        //verifica se o utilizador está "logado"
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        //vai buscar o user_id da tabela userform que esta com login feito e manda para o site/index
        $userForm = UserForm::findOne(['user_id' => Yii::$app->user->id]);
        if (!$userForm) {
            return $this->redirect(['index']);
        }

        //procura o carrinho de compras associado ao utilizador "logado"
        $carrinho = Carrinho::findOne(['userdata_id' => $userForm->id]);

        //remove o Produto(linhacarrinho) do Carrinho
        if ($carrinho) {
            LinhaCarrinho::deleteAll(['carrinho_id' => $carrinho->id, 'produto_id' => $id]);

            //depois de remover, verifica se ainda existem produtos no carrinho utilizando o método exists()
            $temProdutos = LinhaCarrinho::find()->where(['carrinho_id' => $carrinho->id])->exists();

            //se o Carrinho ainda tem Produtos(linhacarrinho), atualiza os valores totais do carrinho
            if ($temProdutos) {
                $this->atualizarTotaisCarrinho($carrinho);
            }

            //se nao houver mais produtos no carrinho, apaga o carrinho
            else {
                $carrinho->delete();
            }
        }

        return $this->redirect(['index']);
    }

    public function actionUpdateQuantidade($id)
    {
        //verifica se o utilizador está "logado"
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        //vai buscar o user_id da tabela userform que esta com login feito e manda para o site/index
        $userForm = UserForm::findOne(['user_id' => Yii::$app->user->id]);
        if (!$userForm) {
            return $this->redirect(['site/index']);
        }

        //procura o carrinho de compras associado ao utilizador "logado"
        $carrinho = Carrinho::findOne(['userdata_id' => $userForm->id]);
        if (!$carrinho) {
            Yii::$app->session->setFlash('error', 'Carrinho não encontrado.');
            return $this->redirect(['index']);
        }

        //obtem o valor da quantidade enviado através de um formulário POST
        //se a quantidade for menor que 1 da erro
        $quantidade = Yii::$app->request->post('quantidade');

        if ($quantidade < 1) {
            Yii::$app->session->setFlash('error', 'A quantidade deve ser pelo menos 1.');
            return $this->redirect(['index']);
        }

        //procura a linha do carrinho com o produto especificado (produto_id)
        $linhaCarrinho = LinhaCarrinho::findOne(['carrinho_id' => $carrinho->id, 'produto_id' => $id]);

        //se a linha for encontrada
        if ($linhaCarrinho) {
            //atualiza:
            //quantidade: para o valor fornecido pelo utilizador
            //subTotal: calculado como o preço do produto (precoVenda) multiplicado pela nova quantidade
            //valorIva: calculado como uma porcentagem do subtotal com base na taxa de IVA do produto
            $linhaCarrinho->quantidade = $quantidade;
            $linhaCarrinho->subTotal = $linhaCarrinho->precoVenda * $linhaCarrinho->quantidade;
            $linhaCarrinho->valorIva = $linhaCarrinho->subTotal * ($linhaCarrinho->produto->iva->percentagem / 100);

            //guarda as alteraçoes, se for bem sucedido chama o metodo atualizarTotaisCarrinho
            if ($linhaCarrinho->save()) {
                $this->atualizarTotaisCarrinho($carrinho);
                Yii::$app->session->setFlash('success', 'Quantidade atualizada com sucesso!');
            } else {
                Yii::$app->session->setFlash('error', 'Erro ao atualizar a quantidade.');
            }
        }
        //se nao encontrar a linhacarrinho, da erro
        else {
            Yii::$app->session->setFlash('error', 'Produto não encontrado no carrinho.');
        }

        return $this->redirect(['index']);
    }

    private function atualizarTotaisCarrinho($carrinho)
    {
        //LinhaCarrinho::find(): procura todas as linhas do carrinho associadas ao carrinho_id
        //where(['carrinho_id' => $carrinho->id]): filtra apenas as linhas que pertencem ao carrinho atual
        //sum('subTotal'): soma os valores do campo subTotal (que é o subtotal de cada linha no carrinho)
        //o subTotal de uma linha é calculado como o preço do produto multiplicado pela quantidade.
        // "?? 0:" se a soma for null (por exemplo, se não houver linhas no carrinho), define o total como 0
        //o resultado é atribuído ao campo total do carrinho
        $carrinho->total = LinhaCarrinho::find()
            ->where(['carrinho_id' => $carrinho->id])
            ->sum('subTotal') ?? 0;

        //o mesmo se de cima se aplica aqui
        $carrinho->ivatotal = LinhaCarrinho::find()
            ->where(['carrinho_id' => $carrinho->id])
            ->sum('valorIva') ?? 0;

        //guarda as alteraçoes no carrinho
        $carrinho->save();
    }

    public function actionView($id)
    {
        //procura o modelo da fatura com o ID fornecido
        $model = $this->findModel($id);

        //procura as linhas da fatura associadas
        $linhasFatura = $model->getLinhafaturas()->all();

        return $this->render('view', [
            'model' => $model,
            'linhasFatura' => $linhasFatura, //passa as linhas da fatura para a view
        ]);
    }

    public function actionCheckout()
    {
        // Obter o ID do utilizador
        $userId = Yii::$app->user->id;

        // Procura o UserForm relacionado ao utilizador "logado"
        $userdata = UserForm::findOne(['user_id' => $userId]);
        if ($userdata === null) {
            throw new NotFoundHttpException('UserData não encontrado para o utilizador.');
        }

        // Obter o userdata_id a partir do UserForm
        $userdataId = $userdata->id;

        // Procura o carrinho associado ao userdata_id
        $carrinho = Carrinho::findOne(['userdata_id' => $userdataId]);

        // Se não encontrou o carrinho, aparece o erro
        if ($carrinho === null) {
            throw new NotFoundHttpException('Carrinho não encontrado para o utilizador.');
        }


        // Procura as linhas do carrinho pelo carrinho_id
        $linhasCarrinho = LinhaCarrinho::find()->where(['carrinho_id' => $carrinho->id])->all();



        // Procura os métodos de pagamento e expedição
        $metodosPagamento = MetodoPagamento::find()->all();
        $metodosExpedicao = MetodoExpedicao::find()->all();

        // Renderiza a view
        return $this->render('checkout', [
            'linhasCarrinho' => $linhasCarrinho,
            'metodosPagamento' => $metodosPagamento,
            'metodosExpedicao' => $metodosExpedicao,
        ]);
    }
}