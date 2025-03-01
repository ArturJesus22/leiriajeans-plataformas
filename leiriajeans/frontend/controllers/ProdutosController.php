<?php

namespace frontend\controllers;

use common\models\Categoria;
use common\models\Imagem;
use common\Models\Produto;
use frontend\Models\ProdutoSearch;
use yii\filters\AccessControl;
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
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [ 'view', 'create'],
                            'roles' => ['admin', 'funcionario', 'cliente'],
                        ],
                        ['allow' => true,
                            'actions' => ['index']]
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
        // Inicia a query de produtos
        $query = Produto::find();

        // Carrega os tipos e sexos de categorias distintas da base de dados
        $tiposDisponiveis = Categoria::find()
            ->select('tipo')
            ->distinct() // Evita duplicações de tipos
            ->all();

        $sexosDisponiveis = Categoria::find()
            ->select('sexo')
            ->distinct() // Evita duplicações de sexos
            ->all();

        // Se o parâmetro tipo for fornecido, filtrar os produtos por tipo
        if ($tipo) {
            // Encontra as categorias que correspondem ao tipo especificado
            $categoriaIds = Categoria::find()
                ->select('id')
                ->where(['tipo' => $tipo])
                ->column();

            // Aplica o  filtro de categoria à query de produtos
            $query->andWhere(['categoria_id' => $categoriaIds]);
        }

        // Se o parâmetro sexo for fornecido, filtra os produtos por sexo
        if ($sexo) {
            // Encontra as categorias que correspondem ao sexo especificado
            $categoriaIds = Categoria::find()
                ->select('id')
                ->where(['sexo' => $sexo])
                ->column();

            // Aplica o filtro de categoria à query de produtos
            $query->andWhere(['categoria_id' => $categoriaIds]);
        }

        // Cria o dataProvider para paginação dos produtos
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 9999999, // Número de produtos por página
            ],
        ]);

        // Cria o model de pesquisa
        $searchModel = new ProdutoSearch();

        // Passa os tipos e sexos para a view
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tiposDisponiveis' => $tiposDisponiveis,
            'sexosDisponiveis' => $sexosDisponiveis,
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
        $imagensAssociadas = Imagem::findAll(['produto_id' => $id]); // Procura as imagens existentes

        return $this->render('view', [
            'model' => $this->findModel($id),
            'imagensAssociadas' => $imagensAssociadas, // Passa as imagens associadas
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