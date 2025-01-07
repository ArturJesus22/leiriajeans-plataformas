<?php

namespace backend\controllers;

use common\models\Imagem;
use common\models\Produto;
use backend\models\ProdutoSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii;

/**
 * ProdutosController implements the CRUD actions for Produtos model.
 */
class ProdutosController extends Controller
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
     * Lists all Produtos models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProdutoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Produtos model.
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
     * Creates a new Produtos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Produto();
        $modelImagens = new Imagem();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {

                $modelImagens->imageFiles = UploadedFile::getInstances($modelImagens, 'imageFiles');
                $modelImagens->produto_id = $model->id;
                if ($modelImagens->imageFiles) {
                    // Chama o método de upload
                    $uploadPaths = $modelImagens->upload();
                    if ($uploadPaths !== false) {
                        foreach ($modelImagens->imageFiles as $index => $file) {
                            $imagem = new Imagem();
                            $imagem->produto_id = $model->id;
                            $imagem->fileName = basename($uploadPaths[$index]);
                            if (!$imagem->save()) {
                                Yii::error("Erro ao guardar imagem: ");
                            }
                        }
                    } else {
                        Yii::error("Erro na validação das imagens.");
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'modelImagens' => $modelImagens,
        ]);
    }









    /**
     * Updates an existing Produtos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelImagens = new Imagem(); // Para o upload de novos arquivos
        $imagensAssociadas = Imagem::findAll(['produto_id' => $id]); // Buscar imagens existentes

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                // Carregar os arquivos
                $modelImagens->imageFiles = UploadedFile::getInstances($modelImagens, 'imageFiles');
                $modelImagens->produto_id = $model->id;

                if (!empty($modelImagens->imageFiles)) {
                    // Realizar o upload
                    $uploadPaths = $modelImagens->upload();


                    if ($uploadPaths !== false) {
                        foreach ($uploadPaths as $path) {
                            $imagem = new Imagem();
                            $imagem->produto_id = $model->id;
                            $imagem->fileName = basename($path);

                            if (!$imagem->save()) {
                                Yii::error("Erro ao salvar imagem no banco: {$path}");
                            }
                        }
                    } else {
                        Yii::error("Erro no upload de imagens.");
                    }
                } else {
                    Yii::warning("Nenhuma imagem foi enviada.");
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('update', [
            'model' => $model,
            'modelImagens' => $modelImagens,
            'imagensAssociadas' => $imagensAssociadas, // Passa as imagens associadas
        ]);
    }


    /**
     * Deletes an existing Produtos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // obter o produto
        $produto = $this->findModel($id);

        // imagens associadas
        $imagens = $this->findModelImg($id);

        foreach ($imagens as $imagem) {
            $backendPath = Yii::getAlias('@web/public/imagens/produtos/' . $imagem->fileName);
            $frontendPath = Yii::getAlias('@frontend/web/images/produtos/' . $imagem->fileName);

            // Apagar as imagens
            if (file_exists($backendPath)) {
                unlink($backendPath);
            }

            if (file_exists($frontendPath)) {
                unlink($frontendPath);
            }

            // Apagar imagem
            $imagem->delete();
        }

        // Apagar o produto
        $produto->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Produtos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Produto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Produto::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    protected function findModelImg($produtoId)
    {
        $imagens = Imagem::findAll(['produto_id' => $produtoId]);
        if ($imagens !== null && !empty($imagens)) {
            return $imagens;
        }
        throw new NotFoundHttpException('Nenhuma imagem foi encontrada para este produto.');
    }


}
