<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use backend\modules\api\components\CustomAuth;
use yii\filters\auth\QueryParamAuth;

class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';
    public $modelUserForm = 'common\models\UserForm';

    // Comportamentos do controlador (ex: autenticação)
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::class,
        ];

        return $behaviors;
    }

    public function actionPing()
    {
        return ['status' => 'Module API is working'];
    }

    // Método para buscar dados do utilizador pelo username
    public function actionDados($username)
    {
        // Procura o utilizador pelo username
        $modelUser = new $this->modelClass;
        $user = $modelUser::find()->where(['username' => $username])->one();

        // Verifica se o utilizador existe
        if ($user === null) {
            throw new NotFoundHttpException("O Utilizador {$username} não foi encontrado");
        }

        // Procura os dados adicionais do utilizador (se existir)
        $usersFormModel = new $this->modelUserForm;
        $userForm = $usersFormModel::find()->where(['user_id' => $user->id])->one();

        // Devolve tanto o utilizador quanto o formulário do utilizador
        return [
            'user' => $user,
            'userForm' => $userForm,
        ];
    }

    // Método para buscar dados do utilizador pelo ID
    public function actionGetUserById($id)
    {
        // Procura o utilizador pelo ID
        $modelUser = new $this->modelClass;
        $user = $modelUser::find()->where(['id' => $id])->one();

        // Verifica se o utilizador existe
        if ($user === null) {
            throw new NotFoundHttpException("O Utilizador com ID {$id} não foi encontrado");
        }

        // Procura os dados adicionais do utilizador (se existir)
        $usersFormModel = new $this->modelUserForm;
        $userForm = $usersFormModel::find()->where(['user_id' => $user->id])->one();

        // Devolve apenas os dados do formulário do utilizador
        return [
            'user' => $user,
            'userForm' => $userForm,
        ];
    }

    // Método de ação de índice (caso você precise de uma página inicial)
    public function actionIndex()
    {
        return $this->render('index');
    }
}
