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
    public $modelUserForm = 'common\models\UserForm'; //model dos dados do utilizador

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

    public function actionCriarPerfil($id) {
        $nome  = Yii::$app->request->post('nome');
        $codpostal  = Yii::$app->request->post('codpostal');
        $localidade  = Yii::$app->request->post('localidade');
        $rua = Yii::$app->request->post('rua');
        $nif = Yii::$app->request->post('nif');
        $telefone = Yii::$app->request->post('telefone');

        $userFormModel = new $this->modelUserForm;
        $userFormExists = $userFormModel::find()->where(['user_id' => $id])->one();

        if($userFormExists) {
            $userFormExists->nome = $nome;
            $userFormExists->codpostal = $codpostal;
            $userFormExists->localidade = $localidade;
            $userFormExists->rua = $rua;
            $userFormExists->nif = $nif;
            $userFormExists->telefone = $telefone;
            $userFormExists->save();
        }
        else {
            $userForm = new $this->modelUserForm;
            $userForm->nome = $nome;
            $userForm->codpostal = $codpostal;
            $userForm->localidade = $localidade;
            $userForm->rua = $rua;
            $userForm->nif = $nif;
            $userForm->telefone = $telefone;
            $userForm->user_id = $id;
            $userForm->save();
        }
        return $userFormExists? ['message' => 'Perfil criado com sucesso'] : ['message' => 'Erro ao criar o Perfil'];
    }
}
