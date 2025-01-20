<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use backend\modules\api\components\CustomAuth;
use yii\filters\auth\QueryParamAuth;
use common\models\User;
use common\models\UserForm;


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

    public function actionUpdateDados()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Obter os dados do POST
        $user_id = \Yii::$app->request->post('user_id');
        $nome = \Yii::$app->request->post('nome');
        $codpostal = \Yii::$app->request->post('codpostal');
        $localidade = \Yii::$app->request->post('localidade');
        $rua = \Yii::$app->request->post('rua');
        $nif = \Yii::$app->request->post('nif');
        $telefone = \Yii::$app->request->post('telefone');

        // Log para debug
        \Yii::debug("Dados recebidos: " . json_encode([
                'user_id' => $user_id,
                'nome' => $nome,
                'codpostal' => $codpostal,
                'localidade' => $localidade,
                'rua' => $rua,
                'nif' => $nif,
                'telefone' => $telefone,
            ]));

        // Encontrar o registro do UserForm
        $userForm = UserForm::findOne(['user_id' => $user_id]);

        if (!$userForm) {
            return [
                'success' => false,
                'message' => 'Usuário não encontrado'
            ];
        }

        // Atualizar os dados
        $userForm->nome = $nome;
        $userForm->codpostal = $codpostal;
        $userForm->localidade = $localidade;
        $userForm->rua = $rua;
        $userForm->nif = $nif;
        $userForm->telefone = $telefone;

        // Tentar salvar
        if ($userForm->save()) {
            return [
                'success' => true,
                'message' => 'Dados atualizados com sucesso',
                'data' => $userForm
            ];
        }

        // Se houver erro, retornar os erros
        return [
            'success' => false,
            'message' => 'Erro ao atualizar dados',
            'errors' => $userForm->getErrors()
        ];
    }
}
