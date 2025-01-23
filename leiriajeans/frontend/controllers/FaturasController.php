<?php

namespace frontend\controllers;

use common\Models\Fatura;
use common\models\Carrinho;
use common\models\LinhaCarrinho;
use common\models\LinhaFatura;
use common\models\UserForm;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use common\models\MetodoPagamento;
use common\models\MetodoExpedicao;

/**
 * FaturasController implements the CRUD actions for Fatura model.
 */
class FaturasController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'confirm-status' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create-from-cart', 'confirm-status'],
                        'roles' => ['admin', 'funcionario', 'cliente'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Fatura models.
     *
     * @return string
     */
    public function actionIndex()
    {
        //verificação do utilizador autenticado
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        //procurar os dados do utilizador
        $userForm = UserForm::findOne(['user_id' => Yii::$app->user->id]);
        if (!$userForm) {
            return $this->redirect(['site/index']);
        }

        //obtem as faturas associadas ao utilizador
        //ActiveDataProvider: fornece dados para serem usados em componentes como tabelas ou listas paginadas
        //Fatura::find(): cria uma consulta para buscar registros na tabela associada ao modelo Fatura
        //where(['userdata_id' => $userForm->id]): filtra as faturas onde o campo userdata_id é igual ao id do objeto $userForm
        $dataProvider = new ActiveDataProvider([
            'query' => Fatura::find()->where(['userdata_id' => $userForm->id]), // Verifica se o relacionamento está correto
            'pagination' => [
                'pageSize' => 9999,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function search($params)
    {
        $query = Fatura::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        return $dataProvider;
    }

    /**
     * Displays a single Fatura model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        // Carrega a fatura
        $model = $this->findModel($id);

        // Procura as linhas da fatura
        $linhasFatura = LinhaFatura::find()
            ->with('produto') // Carrega o relacionamento com o produto e a linha do carrinho
            ->where(['fatura_id' => $id])
            ->all();

        // Procura os métodos de pagamento e expedição
        $metodoPagamento = MetodoPagamento::findOne($model->metodopagamento_id);
        $metodoExpedicao = MetodoExpedicao::findOne($model->metodoexpedicao_id);

        return $this->render('view', [
            'model' => $model,
            'linhasFatura' => $linhasFatura, // Passa as linhas da fatura para a view
            'metodoPagamento' => $metodoPagamento, // Passa o modelo do método de pagamento
            'metodoExpedicao' => $metodoExpedicao, // Passa o modelo do método de expedição
        ]);
    }

    /**
     * Updates an existing Fatura model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionConfirmStatus($id)
    {
        $model = $this->findModel($id);
        $model->statusCompra = 'Entregue';

        if ($model->save(true, ['statusCompra'])) {
            Yii::$app->session->setFlash('success', 'Entrega confirmada com sucesso.');
        } else {
            Yii::$app->session->setFlash('error', 'Erro ao confirmar a entrega: ' . print_r($model->errors, true));

            var_dump($model->errors);
            var_dump($model->statusCompra);
            die();
        }

        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing Fatura model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->statuspedido = 'anulada'; // Define como anulada
        $model->save(false); // guarda sem validar para apenas alterar o estado

        // Excluir o modelo
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Fatura model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Fatura the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Fatura::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCreateFromCart()
    {
        // Verifica se o utilizador está autenticado
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        // Obtém o formulário do utilizador (assumindo que há um formulário do utilizador associado ao utilizador atual)
        $userForm = UserForm::findOne(['user_id' => Yii::$app->user->id]);
        if (!$userForm) {
            return $this->redirect(['site/index']);
        }

        // Obtém o carrinho associado ao utilizador
        $carrinho = Carrinho::findOne(['userdata_id' => $userForm->id]);
        if (!$carrinho) {
            Yii::$app->session->setFlash('error', 'Carrinho não encontrado.');
            return $this->redirect(['carrinhos/index']);
        }

        // Obtém as linhas do carrinho
        $linhasCarrinho = LinhaCarrinho::find()->where(['carrinho_id' => $carrinho->id])->all();

        // Se o carrinho estiver vazio, mostra uma mensagem de erro
        if (empty($linhasCarrinho)) {
            Yii::$app->session->setFlash('error', 'O carrinho está vazio.');
            return $this->redirect(['carrinhos/index']);
        }

        // Captura os métodos de pagamento e expedição do formulário
        $metodoPagamentoId = Yii::$app->request->post('metodopagamento_id');
        $metodoExpedicaoId = Yii::$app->request->post('metodoexpedicao_id');

        // Procura os métodos de pagamento e expedição
        $metodoPagamento = MetodoPagamento::findOne($metodoPagamentoId);
        $metodoExpedicao = MetodoExpedicao::findOne($metodoExpedicaoId);

        // Cria a fatura com os dados do carrinho
        $fatura = new Fatura();
        $fatura->userdata_id = $userForm->id;
        $fatura->metodopagamento_id = $metodoPagamentoId;
        $fatura->metodoexpedicao_id = $metodoExpedicaoId;
        $fatura->data = date('Y-m-d H:i:s'); // Data e hora da compra
        $fatura->statuspedido = 'pendente'; // Status inicial é "pendente"
        $fatura->valorTotal = $carrinho->total + $carrinho->ivatotal;

        // Guarda a fatura
        if ($fatura->save()) {
            // Cria as linhas de fatura com base nas linhas do carrinho
            foreach ($linhasCarrinho as $linhaCarrinho) {
                $linhaFatura = new LinhaFatura();
                $linhaFatura->fatura_id = $fatura->id;
                $linhaFatura->quantidade = $linhaCarrinho->quantidade;
                $linhaFatura->precoVenda = $linhaCarrinho->precoVenda;
                $linhaFatura->valorIva = $linhaCarrinho->valorIva;
                $linhaFatura->subTotal = $linhaCarrinho->subTotal;
                $linhaFatura->produto_id = $linhaCarrinho->produto_id;
                //var_dump($linhaFatura);

                if ($linhaFatura->save()) {
                    // Atualiza a quantidade do produto após a compra
                    $produto = $linhaCarrinho->produto;
                    if ($produto) {
                        $produto->stock -= $linhaCarrinho->quantidade; // Retira a quantidade comprada
                        if ($produto->stock < 0) {
                            $produto->stock = 0;
                        }
                        $produto->save();
                    }// Guarda a atualização do produto
                }

                // Após guardar todas as linhas de fatura, atualiza o estado da fatura para "pago"
                $fatura->statuspedido = 'pago'; // Altera o estado para "pago"
                $fatura->save(); // Guarda a fatura com o estado atualizado

                foreach ($linhasCarrinho as $linhaCarrinho) {
                    $linhaCarrinho->delete();
                }

                $carrinho->delete();

//                // Se houver erro ao guardar a linha de fatura, exibe uma mensagem de erro e redireciona
//                if (!$linhaFatura->save()) {
//                    return $this->redirect(['carrinhos/index']);
//                }
            }

            // Define uma mensagem de sucesso e redireciona para a página de visualização da fatura
            Yii::$app->session->setFlash('success', 'Fatura criada com sucesso!');
            return $this->redirect(['view', 'id' => $fatura->id]);
        } else {
            // Se houver erro ao guardar a fatura, mostra uma mensagem de erro e redireciona
            Yii::$app->session->setFlash('error', 'Erro ao criar fatura.');
            return $this->redirect(['carrinhos/index']);
        }
    }

}
