<?php

namespace frontend\controllers;

use common\models\Avaliacao;
use common\models\Fatura;
use common\models\LinhaFatura;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii;

/**
 * AvaliacoesController implements the CRUD actions for Avaliacao model.
 */
class AvaliacoesController extends Controller
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
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['index', 'view', 'create'],
                            'roles' => ['admin', 'funcionario', 'cliente'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Avaliacao models.
     *
     * @return string
     */

    /**
     * Displays a single Avaliacao model.
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
     * Creates a new Avaliacao model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */

    public function actionCreate()
    {
        $avaliacao = new Avaliacao();

        if ($avaliacao->load(Yii::$app->request->post()) && $avaliacao->validate()) {
            // Busca a linha da fatura
            $linhaFatura = LinhaFatura::findOne($avaliacao->linhafatura_id);

            if (!$linhaFatura) {
                throw new \yii\web\ForbiddenHttpException('Linha de fatura inválida.');
            }

            // Busca a fatura
            $fatura = Fatura::findOne($linhaFatura->fatura_id);

            // Verifica se a fatura existe, se pertence ao utilizador e se o status é 'Entregue'
            if (!$fatura ||
                $fatura->userdata_id !== Yii::$app->user->identity->userform->id ||
                $fatura->statusCompra !== 'Entregue') {

                // Mensagem personalizada baseada no erro específico
                if (!$fatura) {
                    throw new \yii\web\ForbiddenHttpException('Fatura não encontrada.');
                } elseif ($fatura->userdata_id !== Yii::$app->user->identity->userform->id) {
                    throw new \yii\web\ForbiddenHttpException('Você não comprou este produto!');
                } else {
                    throw new \yii\web\ForbiddenHttpException('Você só pode avaliar produtos que já foram entregues.');
                }
            }

            // Verifica se o utilizador já avaliou este produto
            $avaliacaoExistente = Avaliacao::find()
                ->where([
                    'linhafatura_id' => $avaliacao->linhafatura_id,
                ])
                ->one();

            if ($avaliacaoExistente) {
                throw new \yii\web\ForbiddenHttpException('Você já avaliou este produto.');
            }

            // Se passou por todas as verificações, guarda a avaliação
            if ($avaliacao->save()) {
                Yii::$app->session->setFlash('success', 'Avaliação registada com sucesso.');
                return $this->redirect(['produtos/view', 'id' => $linhaFatura->produto_id]);
            }
        }

        return $this->render('create', [
            'model' => $avaliacao,
        ]);
    }


    /**
     * Updates an existing Avaliacao model.
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
     * Deletes an existing Avaliacao model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    /**
     * Finds the Avaliacao model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Avaliacao the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Avaliacao::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
