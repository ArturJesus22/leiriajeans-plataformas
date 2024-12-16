<?php

namespace backend\controllers;

use backend\models\AuthAssignment;
use common\models\Cor;
use common\models\LoginForm;
use common\models\Produto;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['backendAccess'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $userIdsWithAdminRole = AuthAssignment::find()
            ->select('user_id')
            ->where(['item_name' => 'admin'])
            ->column();
        $numUsersWithAdminRole = count($userIdsWithAdminRole);

        $userIdsWithClienteRole = AuthAssignment::find()
            ->select('user_id')
            ->where(['item_name' => 'cliente'])
            ->column();
        $numUsersWithClienteRole = count($userIdsWithClienteRole);

        $userIdsWithFuncionarioRole = AuthAssignment::find()
            ->select('user_id')
            ->where(['item_name' => 'funcionario'])
            ->column();
        $numUsersWithFuncionarioRole = count($userIdsWithFuncionarioRole);

        $cores = Cor::find();
        $numCores = $cores -> count();

        $produtos = Produto::find();
        $numProdutos= $produtos-> count();

        return $this->render('index', [
            //PASSAR PARA O INDEX ESTAS VARIAVEIS
            'numUsersWithClienteRole' => $numUsersWithClienteRole,
            'numUsersWithFuncionarioRole' => $numUsersWithFuncionarioRole,
            'numCores' => $numCores,
            'numProdutos' => $numProdutos,
            'numUsersWithAdminRole' => $numUsersWithAdminRole,
        ]);
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if (Yii::$app->user->can('admin') || Yii::$app->user->can('funcionario')) {
                return $this->goHome();
            }
            else{
                Yii::$app->user->logout();
                Yii::$app->session->setFlash('error', 'Você não tem permissão para aceder a esta área.');
                return $this->refresh();
            }
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
