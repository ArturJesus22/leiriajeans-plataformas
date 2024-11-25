<?php

namespace backend\controllers;

use backend\models\UsersSearch;
use common\models\SignupForm;
use common\models\User;
use common\models\UsersForm;
use yii\data\ActiveDataProvider;
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
        $model = $this->findModel($id);
        $modelUserData = UsersForm::findOne(['user_id' => $id]); // UsersForm relacionado
        $rolename = Yii::$app->authManager->getRolesByUser($id);

        foreach ($rolename as $role) {
            $roleName = $role->name;
            $model->role = $roleName;
        }

        //Se não houver um UsersForm relacionado, cria um novo
        if (!$modelUserData) {
            $modelUserData = new UsersForm();
            $modelUserData->user_id = $model->id;
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            $model->load(\Yii::$app->request->post());
            $modelUserData->load(\Yii::$app->request->post());
            //entrar no authManager
            $auth = Yii::$app->authManager;
            $role = $auth->getRole($model->role);
            //apagar a role atual
            $auth -> revokeAll($model->id);
            //atribuir a nova role
            $auth->assign($role, $model->id);


            return $this->redirect(['view', 'id' => $model->id]);
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
        $this->findModel($id)->delete();

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
