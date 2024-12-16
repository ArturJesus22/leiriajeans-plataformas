<?php

namespace frontend\controllers;

use common\Models\Fatura;
use common\models\Carrinho;
use common\models\LinhaCarrinho;
use common\models\LinhaFatura;
use common\models\UserForm;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

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
        $query = Fatura::find()
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
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Fatura model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Fatura();

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

    /**
     * Deletes an existing Fatura model.
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

        $userForm = UserForm::findOne(['user_id' => Yii::$app->user->id]);
        if (!$userForm) {
            return $this->redirect(['site/index']);
        }

        // Busca o carrinho do usuário
        $carrinho = Carrinho::findOne(['userdata_id' => $userForm->id]);
        if (!$carrinho) {
            Yii::$app->session->setFlash('error', 'Carrinho não encontrado.');
            return $this->redirect(['carrinhos/index']);
        }

        // Cria uma nova fatura
        $fatura = new Fatura();
        $fatura->metodopagamento_id = 1; // Defina o método de pagamento conforme necessário
        $fatura->metodoexpedicao_id = 1; // Defina o método de expedição conforme necessário
        $fatura->data = date('Y-m-d H:i:s');
        $fatura->valorTotal = $carrinho->total + $carrinho->ivatotal; // Total da fatura

        if ($fatura->save()) {
            // Busca as linhas do carrinho
            $linhasCarrinho = LinhaCarrinho::find()->where(['carrinho_id' => $carrinho->id])->all();

            foreach ($linhasCarrinho as $linha) {
                // Cria uma nova linha de fatura
                $linhaFatura = new LinhaFatura();
                $linhaFatura->fatura_id = $fatura->id;
                $linhaFatura->linhacarrinho_id = $linha->id; // Associa a linha do carrinho
                $linhaFatura->iva_id = $linha->produto->iva_id; // Assumindo que o produto tem um campo iva_id
                $linhaFatura->preco = $linha->precoVenda; // Preço da linha

                $linhaFatura->save(); // Salva a linha de fatura
            }

            // Limpa o carrinho após a criação da fatura
            LinhaCarrinho::deleteAll(['carrinho_id' => $carrinho->id]);
            $carrinho->delete();

            Yii::$app->session->setFlash('success', 'Fatura criada com sucesso!');
            return $this->redirect(['view', 'id' => $fatura->id]);
        } else {
            Yii::$app->session->setFlash('error', 'Erro ao criar fatura.');
            return $this->redirect(['carrinhos/index']);
        }
    }
}
