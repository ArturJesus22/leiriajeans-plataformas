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

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::class,
        ];

        return $behaviors;
    }

    // Método verificar se a API está a funcionar
    public function actionPing()
    {
        return ['status' => 'Module API is working'];
    }

    // Método para obter os dados do utilizador pelo username
    public function actionDados($username)
    {
        //procura pelo username
        $modelUser = new $this->modelClass;
        $user = $modelUser::find()->where(['username' => $username])->one();

        //verifica se o utilizador existe
        if ($user === null) {
            throw new NotFoundHttpException("O Utilizador {$username} não foi encontrado");
        }

        //procura todos os dados do utilizador (se existir)
        $usersFormModel = new $this->modelUserForm;
        $userForm = $usersFormModel::find()->where(['user_id' => $user->id])->one();

        return [
            'user' => $user,
            'userForm' => $userForm,
        ];
    }

    // Método para buscar dados do utilizador pelo ID
    public function actionGetUserById($id)
    {
        //procura o utilizador pelo id
        $modelUser = new $this->modelClass;
        $user = $modelUser::find()->where(['id' => $id])->one();

        //verifica se o utilizador existe
        if ($user === null) {
            throw new NotFoundHttpException("O Utilizador com ID {$id} não foi encontrado");
        }

        //procura todos os dados do utilizador (se existir)

        $usersFormModel = new $this->modelUserForm;
        $userForm = $usersFormModel::find()->where(['user_id' => $user->id])->one();

        return [
            'user' => $user,
            'userForm' => $userForm,
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}
