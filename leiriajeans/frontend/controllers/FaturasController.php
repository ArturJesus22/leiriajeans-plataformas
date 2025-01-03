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
        $user_id = Yii::$app->user->identity->id;

        $query = Fatura::find()
            ->where(['userdata_id' => $user_id])
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
        // Carregar a fatura
        $model = $this->findModel($id);
        
        // Buscar as linhas da fatura
        $linhasFatura = LinhaFatura::find()
            ->with('produto') // Carrega o relacionamento com o produto e a linha do carrinho
            ->where(['fatura_id' => $id])
            ->all();



        return $this->render('view', [
            'model' => $model,
            'linhasFatura' => $linhasFatura, // Passa as linhas da fatura para a view
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
        // Verifica se o usuário está autenticado
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        // Obtém o formulário do usuário (assumindo que há um formulário do usuário associado ao usuário atual)
        $userForm = UserForm::findOne(['user_id' => Yii::$app->user->id]);
        if (!$userForm) {
            return $this->redirect(['site/index']);
        }

        // Obtém o carrinho associado ao usuário
        $carrinho = Carrinho::findOne(['userdata_id' => $userForm->id]);
        if (!$carrinho) {
            Yii::$app->session->setFlash('error', 'Carrinho não encontrado.');
            return $this->redirect(['carrinhos/index']);
        }

        // Obtém as linhas do carrinho
        $linhasCarrinho = LinhaCarrinho::find()->where(['carrinho_id' => $carrinho->id])->all();

        // Se o carrinho estiver vazio, exibe uma mensagem de erro
        if (empty($linhasCarrinho)) {
            Yii::$app->session->setFlash('error', 'O carrinho está vazio.');
            return $this->redirect(['carrinhos/index']);
        }

        // Cria a fatura com os dados do carrinho
        $fatura = new Fatura();
        $fatura->userdata_id = $userForm->id;
        $fatura->metodopagamento_id = 1; // Ajuste conforme necessário
        $fatura->metodoexpedicao_id = 1; // Ajuste conforme necessário
        $fatura->data = date('Y-m-d');
        $fatura->valorTotal = $carrinho->total + $carrinho->ivatotal;

        // Salva a fatura
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
                $linhaFatura->save();
                //var_dump($linhaFatura);

                foreach ($linhasCarrinho as $linhaCarrinho) {
                    $linhaCarrinho->delete();
                }

                $carrinho->delete();

//                // Se houver erro ao salvar a linha de fatura, exibe uma mensagem de erro e redireciona
//                if (!$linhaFatura->save()) {
//                    return $this->redirect(['carrinhos/index']);
//                }
            }

            // Define uma mensagem de sucesso e redireciona para a página de visualização da fatura
            Yii::$app->session->setFlash('success', 'Fatura criada com sucesso!');
            return $this->redirect(['view', 'id' => $fatura->id]);
        } else {
            // Se houver erro ao salvar a fatura, exibe uma mensagem de erro e redireciona
            Yii::$app->session->setFlash('error', 'Erro ao criar fatura.');
            return $this->redirect(['carrinhos/index']);
        }
    }



}
