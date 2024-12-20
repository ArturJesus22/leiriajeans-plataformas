<?php

namespace frontend\controllers;

use common\models\Categoria;
use common\models\Imagem;
use common\Models\Produto;
use frontend\Models\ProdutoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
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
            ]
        );
    }

    /**
     * Lists all Produtos models.
     *
     * @return string
     */

    public function actionIndex($sexo = null, $tipo = null)
    {
        // Iniciar a query de produtos
        $query = Produto::find();

        // Se o parâmetro tipo for fornecido, filtrar pelo tipo
        if ($tipo) {
            // Encontrar categorias que correspondem ao tipo especificado
            $categoriaIds = Categoria::find()
                ->select('id')
                ->where(['tipo' => $tipo])
                ->column();

            // Aplicar filtro de categoria à query de produtos
            $query->andWhere(['categoria_id' => $categoriaIds]);
        }

        // Se o parâmetro sexo for fornecido, filtrar pelo sexo
        if ($sexo) {
            $sexos = is_array($sexo) ? $sexo : [$sexo];

            $categoriaIds = Categoria::find()
                ->select('id')
                ->where(['sexo' => $sexos])
                ->column();

            $query->andWhere(['categoria_id' => $categoriaIds]);
        }

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 12, // Número de produtos por página
            ],
        ]);

        // Criar o model de pesquisa
        $searchModel = new ProdutoSearch();

        // Renderizar a view
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

        // Criar o model de pesquisa
        $searchModel = new ProdutoSearch();

        // Renderizar a view
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
        $imagensAssociadas = Imagem::findAll(['produto_id' => $id]); // Buscar imagens existentes

        return $this->render('view', [
            'model' => $this->findModel($id),
            'imagensAssociadas' => $imagensAssociadas, // Passar as imagens associadas
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
     * Updates an existing Produtos model.
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
     * Deletes an existing Produtos model.
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
}