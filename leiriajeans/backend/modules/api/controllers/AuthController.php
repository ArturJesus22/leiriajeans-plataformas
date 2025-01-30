<?php
namespace backend\modules\api\controllers;
use yii\base\Behavior;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii;
use yii\web\Controller;
use backend\modules\api\components\CustomAuth;

class AuthController extends ActiveController
{
    public $modelClass = 'common\models\User';
    public $modelUserForm = 'common\models\UserForm';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        return $behaviors;
    }


    public function actionLogin() {
        //obter os dados
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');

        // Validar os dados de entrada
        if (empty($username) || empty($password)) {
            return ['message' => 'Todos os campos são obrigatórios.'];
        }

        $user = $this->modelClass::find()->where(['username' => $username])->one();
        if ($user == null) {
            throw new \yii\web\NotFoundHttpException("Não existe o utilizador " . $username);
        }

        if ($user->validatePassword($password)) {
            return $user;
        } else {
            return ['message' => 'Erro nas credenciais'];
        }
    }

    public function actionSignup() {

        //obter os dados
        $username = Yii::$app->request->post('username');
        $email = Yii::$app->request->post('email');
        $password = Yii::$app->request->post('password_hash');
        $nome = Yii::$app->request->post('nome');
        $codpostal = Yii::$app->request->post('codpostal');
        $localidade = Yii::$app->request->post('localidade');
        $rua = Yii::$app->request->post('rua');
        $nif = Yii::$app->request->post('nif');
        $telefone = Yii::$app->request->post('telefone');
        $role = ('cliente');


        //validar dados
        if (empty($nome) || empty($username) || empty($password) ||empty($email)  || empty($codpostal) || empty($localidade) || empty($rua) || empty($nif) || empty($telefone)) {
            return ['message' => 'Todos os campos são obrigatórios.'];
        }


        //criar novo utilizador
        $userModel = new $this->modelClass;
        $userModel->username = $username;
        $userModel->email = $email;
        $userModel->role = $role;
        $userModel->setPassword($password);
        $userModel->generateAuthKey();
        $userModel->generateEmailVerificationToken();



        //guardar o utilizador
        if ($userModel->save()) {
            //depois de guardar o utilizador, criar o perfil associado
            $userFormModel = new $this->modelUserForm;
            $userFormModel->user_id = $userModel->id; //associar o perfil ao utilizador

            // guardar o utilizador
            if ($userModel->save()) {
                // Após guardar o utilizador, criar o perfil associado
                $userFormModel = new $this->modelUserForm;
                $userFormModel->user_id = $userModel->id; // associar o perfil ao utilizador
                $userFormModel->nome = $nome;
                $userFormModel->codpostal = $codpostal;
                $userFormModel->localidade = $localidade;
                $userFormModel->rua = $rua;
                $userFormModel->nif = $nif;
                $userFormModel->telefone = $telefone;

                if ($userFormModel->validate()) {
                    if ($userFormModel->save()) {
                        return ['message' => 'Utilizador e perfil criados com sucesso'];
                    } else {
                        return ['message' => 'Erro ao guardar o perfil do utilizador', 'errors' => $userFormModel->errors];
                    }
                } else {
                    return ['message' => 'Erro na validação do perfil do utilizador', 'errors' => $userFormModel->errors];
                }
            } else {
                return ['message' => 'Erro ao criar o utilizador', 'errors' => $userModel->errors];
            }
        }
    }
}

