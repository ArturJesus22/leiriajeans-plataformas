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

        return $behaviors;
    }

    public function actionIndex()
    {
        return ['message' => 'Autenticado com sucesso'];
        //return $this->render('index');
    }

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

}
