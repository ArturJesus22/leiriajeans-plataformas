<?php

namespace frontend\controllers;

use common\Models\Faturas;
use common\models\Carrinhos;
use common\models\LinhasCarrinhos;
use common\models\LinhasFaturas;
use common\models\UsersForm;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * FaturasController implements the CRUD actions for Faturas model.
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
                ],
            ],
        ];
    }

    /**
     * Lists all Faturas models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $query = Faturas::find()
            ->with(['linhafaturas', 'linhafaturas.linhacarrinho', 'linhafaturas.linhacarrinho.produto'])
            ->orderBy(['data' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function search($params)
    {
        $query = Faturas::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        return $dataProvider;
    }

    /**
     * Displays a single Faturas model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Faturas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Faturas();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Faturas model.
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

    /**
     * Deletes an existing Faturas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Faturas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Faturas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Faturas::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

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

    public function actionCreateFromCart()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $userForm = UsersForm::findOne(['user_id' => Yii::$app->user->id]);
        if (!$userForm) {
            return $this->redirect(['site/index']);
        }

        // Busca o carrinho do usuário
        $carrinho = Carrinhos::findOne(['userdata_id' => $userForm->id]);
        if (!$carrinho) {
            Yii::$app->session->setFlash('error', 'Carrinho não encontrado.');
            return $this->redirect(['carrinhos/index']);
        }

        // Cria uma nova fatura
        $fatura = new Faturas();
        $fatura->metodopagamento_id = 1; // Defina o método de pagamento conforme necessário
        $fatura->metodoexpedicao_id = 1; // Defina o método de expedição conforme necessário
        $fatura->data = date('Y-m-d H:i:s');
        $fatura->valorTotal = $carrinho->total + $carrinho->ivatotal; // Total da fatura

        if ($fatura->save()) {
            // Busca as linhas do carrinho
            $linhasCarrinho = LinhasCarrinhos::find()->where(['carrinho_id' => $carrinho->id])->all();

            foreach ($linhasCarrinho as $linha) {
                // Cria uma nova linha de fatura
                $linhaFatura = new LinhasFaturas();
                $linhaFatura->fatura_id = $fatura->id;
                $linhaFatura->linhacarrinho_id = $linha->id; // Associa a linha do carrinho
                $linhaFatura->iva_id = $linha->produto->iva_id; // Assumindo que o produto tem um campo iva_id
                $linhaFatura->preco = $linha->precoVenda; // Preço da linha

                $linhaFatura->save(); // Salva a linha de fatura
            }

            // Limpa o carrinho após a criação da fatura
            LinhasCarrinhos::deleteAll(['carrinho_id' => $carrinho->id]);
            $carrinho->delete();

            Yii::$app->session->setFlash('success', 'Fatura criada com sucesso!');
            return $this->redirect(['view', 'id' => $fatura->id]);
        } else {
            Yii::$app->session->setFlash('error', 'Erro ao criar fatura.');
            return $this->redirect(['carrinhos/index']);
        }
    }
}
