<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use backend\modules\api\components\CustomAuth;
use yii\filters\auth\QueryParamAuth;

class UserController extends ActiveController
{
    public $modelClass = 'common\models\User'; // Modelo padrão de usuário
    public $modelUserForm = 'common\models\UserForm'; // Modelo dos dados do utilizador

    // Comportamentos do controlador (ex: autenticação)
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::class,
        ];

        return $behaviors;
    }

    // Método de ping para verificar se a API está funcionando
    public function actionPing()
    {
        return ['status' => 'Module API is working'];
    }

    // Método para buscar dados do utilizador pelo username
    public function actionDados($username)
    {
        // Busca o usuário pelo username
        $modelUser = new $this->modelClass;
        $user = $modelUser::find()->where(['username' => $username])->one();

        // Verifica se o usuário existe
        if ($user === null) {
            throw new NotFoundHttpException("O Utilizador {$username} não foi encontrado");
        }

        // Busca os dados adicionais do usuário (se existir)
        $usersFormModel = new $this->modelUserForm;
        $userForm = $usersFormModel::find()->where(['user_id' => $user->id])->one();

        // Retorna tanto o usuário quanto o formulário do usuário
        return [
            'user' => $user,
            'userForm' => $userForm,
        ];
    }

    // Método para buscar dados do utilizador pelo ID
    public function actionGetUserById($id)
    {
        // Busca o usuário pelo ID
        $modelUser = new $this->modelClass;
        $user = $modelUser::find()->where(['id' => $id])->one();

        // Verifica se o usuário existe
        if ($user === null) {
            throw new NotFoundHttpException("O Utilizador com ID {$id} não foi encontrado");
        }

        // Busca os dados adicionais do usuário (se existir)
        $usersFormModel = new $this->modelUserForm;
        $userForm = $usersFormModel::find()->where(['user_id' => $user->id])->one();

        // Retorna apenas os dados do formulário do utilizador
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
