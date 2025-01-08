<?php

namespace backend\controllers;

use backend\models\UsersSearch;
use common\models\SignupForm;
use common\models\User;
use common\models\UserForm;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
                        'Colaboradores' => 'GET',
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['index', 'view', 'create', 'update', 'delete', 'perfil'],
                            'roles' => ['admin', 'funcionario'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all User models.
     *
     * @return string
     */

    public function actionIndex()
    {

        if(Yii::$app->user->can('admin')){
            $query = User::find()
                ->joinWith('authAssignment');
        }
        else if(Yii::$app->user->can('funcionario')){
            $query = User::find()
                ->joinWith('authAssignment') // Faz o join com a tabela auth_assignment
                ->where(['auth_assignment.item_name' => 'cliente']); // Filtro para item_name 'cliente'
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single User model.
     * @param int $id
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        // Encontra o modelo principal User
        $model = $this->findModel($id);

        // Procura o modelo UserForm relacionado
        $modelUserData = UserForm::findOne(['user_id' => $id]);

        // Obtem as roles do utilizador atual
        $rolename = Yii::$app->authManager->getRolesByUser($id);

        // Atribuir a role ao modelo principal User
        foreach ($rolename as $role) {
            $roleName = $role->name;
            $model->role = $roleName;
        }

        // Se não houver um UserForm relacionado, cria um novo
        if (!$modelUserData) {
            $modelUserData = new UserForm();
            $modelUserData->user_id = $model->id;
        }

        $isOwnAccount = Yii::$app->user->id == $id;
        $currentUserRole = Yii::$app->authManager->getRolesByUser(Yii::$app->user->id);
        $currentUserRoleName = key($currentUserRole); // Obtém o nome da role atual do utilizador logado

        // Verificar se a requisição é um POST e se os modelos são válidos
        if ($this->request->isPost) {
            // Carregar os dados do modelo User e UserForm
            if ($model->load($this->request->post()) && $modelUserData->load($this->request->post())) {

                if ($isOwnAccount) {
                    $model->role = $roleName; // Recuperar a role original
                    Yii::$app->session->setFlash('warning', 'Você não pode alterar sua própria role.');
                }

                if ($currentUserRoleName === 'funcionario' && $model->role !== $roleName) {
                    Yii::$app->session->setFlash('error', 'Você não tem permissão para alterar a role de um utilizador.');
                    $model->role = $roleName; // Restaura a role original
                }


                // Salvar o modelo User
                if ($model->save()) {
                    // Entrar no authManager
                    $auth = Yii::$app->authManager;
                    $role = $auth->getRole($model->role);

                    // Apagar a role atual
                    $auth->revokeAll($model->id);

                    // Atribuir a nova role
                    $auth->assign($role, $model->id);

                    // Salvar os dados do modelo UserForm
                    if ($modelUserData->save()) {
                        // Redirecionar para a visualização do modelo atualizado
                        return $this->redirect(['view', 'id' => $model->id]);
                    } else {
                        // Se o modelo UserForm não for guardadado, adicionar mensagem de erro
                        Yii::$app->session->setFlash('error', 'Erro ao salvar os dados adicionais do usuário.');
                    }
                } else {
                    // Se o modelo UserForm não for guardadado, adicionar mensagem de erro
                    Yii::$app->session->setFlash('error', 'Erro ao salvar o usuário.');
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelUserData' => $modelUserData,
        ]);
    }


    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        // Atualiza o status para 'inactive'
        $model->status = 9;
        $model->save(false);

        return $this->redirect(['index']);
    }


    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionPerfil() {
        $userId = \Yii::$app->user->id; //ID do user com login
        $model = User::findOne($userId); // Dados do utilizador

        return $this->render('view', [
            'model' => $model,
        ]);
    }

}
