<?php
namespace backend\modules\api\controllers;
use yii\base\Behavior;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii;
use yii\web\Controller;
use backend\modules\api\components\CustomAuth;

class UserController extends ActiveController
{
    public $modelClass = 'common\models\User'; //model default user
    public $modelUserForm = 'common\models\UsersForm'; //model dos dados do utilizador

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(), //autenticação por token
        ];
        return $behaviors;
    }

    public function index()
    {
        return ['message' => 'Autenticado com sucesso'];
        //return $this->render('index');
    }

//    public function actionNomes(){
//        $usermodel = new $this->modelClass;
//        $recs = $usermodel::find()->select(['username'])->all();
//        return [$recs];
//    }

    public function actionDados($username)
    {
        $modelUser = new $this->modelClass;
        $user = $modelUser::find()->where(['username' => $username])->one();
        if($user == null)
        {
            throw new \yii\web\NotFoundHttpException("O Utilizador" . $username . "não foi encontrado");
        }
        $usersFormModel = new $this->modelUserForm;
        $userForm = $usersFormModel::find()->where(['user_id' => $user->id])->one();
        return [$user, $userForm];
    }

    //Procurar User Pelo ID
    //getuserbyid
    public function actionGetUserById($id)
    {
        $modelUser = new $this->modelClass;
        $user = $modelUser::find()->where(['id' => $id])->one();
        if($user == null)
        {
            throw new \yii\web\NotFoundHttpException("O Utilizador com ID ". $id . "não foi encontrado");
        }
        $usersFormModel = new $this->modelUserForm;
        $userForm = $usersFormModel::find()->where(['user_id' => $user->id])->one();
        return [$userForm];
    }

    //Criar perfil actionCriarperfil($id)



    public function actionSignup() {
        // Obter os dados da requisição
        $username = Yii::$app->request->post('username');
        $email = Yii::$app->request->post('email');
        $password = Yii::$app->request->post('password_hash');
        $nome = Yii::$app->request->post('nome');
        $codpostal = Yii::$app->request->post('codpostal');
        $localidade = Yii::$app->request->post('localidade');
        $rua = Yii::$app->request->post('rua');
        $nif = Yii::$app->request->post('nif');
        $telefone = Yii::$app->request->post('telefone');

        // Validar dados de entrada
        if (empty($nome) || empty($username) || empty($password) ||empty($email)  || empty($codpostal) || empty($localidade) || empty($rua) || empty($nif) || empty($telefone)) {
            return ['message' => 'Todos os campos são obrigatórios.'];
        }

        // Criar o novo utilizador
        $userModel = new $this->modelClass;
        $userModel->username = $username;
        $userModel->email = $email;
        $userModel->setPassword($password);
        $userModel->generateAuthKey();
        $userModel->generateEmailVerificationToken();



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

            // guardar o perfil do utilizador
            if ($userFormModel->save()) {
                return ['message' => 'Utilizador e perfil criados com sucesso'];
            } else {
                return ['message' => 'Erro ao criar o perfil do Utilizador'];
            }
        } else {
            return ['message' => 'Erro ao criar o utilizador'];
        }
    }
}
