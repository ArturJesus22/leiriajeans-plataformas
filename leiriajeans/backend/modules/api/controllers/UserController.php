<?php
namespace backend\modules\api\controllers;
use yii\base\Behavior;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
class UserController extends ActiveController
{
    public $modelClass = 'common\models\User'; //model default user
    public $modelUserData = ''; //model dos dados do utilizador

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(), //autenticação por token
        ];
    }

    public function index()
    {
        return $this->render('index');
    }

    public function actionDados($username)
    {
        $modelUser = new $this->modelClass;
        $user = $modelUser::find()->where(['username' => $username])->one();
        if($user == null)
        {
            throw new \yii\web\NotFoundHttpException("O Utilizador" . $username . "não foi encontrado");
        }
        //FALTA COLOCAR PARTE DO modelUserData , INCOMPLETO
        return;
    }
}
