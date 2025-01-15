<?php

namespace backend\controllers;

use common\models\Imagem;
use backend\models\ImagemSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii;

/**
 * ImagensController implements the CRUD actions for Imagem model.
 */
class ImagensController extends Controller
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
                            'actions' => ['index', 'view', 'create', 'update', 'delete'],
                            'roles' => ['admin', 'funcionario'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Imagem models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ImagemSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Imagem model.
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
     * Creates a new Imagem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Imagem();

        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Imagem model.
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
     * Deletes an existing Imagem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id); // Carrega o modelo da imagem pelo ID

        // Caminho para as imagens no backend e frontend
        $backendPath = Yii::getAlias('@backend/web/public/imagens/produtos/' . $model->fileName);
        $frontendPath = Yii::getAlias('@frontend/web/images/produtos/' . $model->fileName);

        // Apagar arquivos , se existirem
        if (file_exists($backendPath)) {
            unlink($backendPath);
        }

        if (file_exists($frontendPath)) {
            unlink($frontendPath);
        }

        $model->delete();

        // Redirecionar para o index
        return $this->redirect(['index']);
    }

    /**
     * Finds the Imagem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Imagem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Imagem::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
