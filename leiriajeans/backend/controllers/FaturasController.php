<?php

namespace backend\controllers;

use common\models\Fatura;
use backend\models\FaturaSearch;
use common\models\Linhafatura;
use common\models\MetodoExpedicao;
use common\models\MetodoPagamento;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                        'enviar' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['index', 'view', 'update', 'confirm-status', 'pendentes', 'enviar'],
                            'roles' => ['admin', 'funcionario'],
                        ],
                    ],
                ],
            ]
        );
    }


    /**
     * Lists all Fatura models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new FaturaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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

        if ($this->request->isPost) {
            // carrega apenas o campo statusCompra
            $model->statusCompra = \Yii::$app->request->post('Fatura')['statusCompra'];

            if ($model->save(false, ['statusCompra'])) { // guarda apenas o campo statusCompra
                \Yii::$app->session->setFlash('success', 'Status atualizado com sucesso.');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                \Yii::$app->session->setFlash('error', 'Erro ao atualizar status: ' . var_dump($model->errors, true));
            }
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

    public function actionPendentes()
    {
        $searchModel = new FaturaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $dataProvider->query->andWhere(['statusCompra' => ['Em Processamento']]);

        return $this->render('pendentes', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionEnviar($id)
    {
        $model = $this->findModel($id);

        if ($model->statusCompra === 'Em Processamento') {
            $model->statusCompra = 'Enviado';
            if ($model->save(false)) { // Adicionado false para ignorar validação
                \Yii::$app->session->setFlash('success', 'Pedido marcado como enviado com sucesso.');
            } else {
                \Yii::$app->session->setFlash('error', 'Erro ao atualizar o estado do pedido.');
            }
        }

        return $this->redirect(['view', 'id' => $id]);
    }



}
