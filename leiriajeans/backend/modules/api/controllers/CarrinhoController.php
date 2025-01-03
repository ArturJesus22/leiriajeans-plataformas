<?php
namespace backend\modules\api\controllers;


use Carbon\Carbon;
use yii\filters\auth\QueryParamAuth;
use yii\web\Controller;
use yii\rest\ActiveController;
use yii;

class CarrinhoController extends ActiveController
{
    public $modelClass = 'common\models\Carrinho';
    public $produtoModelClass = 'common\models\Produto';
    public $userModelClass = 'common\models\User';
    public $linhaCarrinhoModelClass = 'common\models\LinhaCarrinho';

    public $metodopagamentoModelClass = 'common\models\MetodoPagamento';
    public $modelUserForm = 'common\models\UsersForm';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
        ];
        return $behaviors;
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    //function to get a carrinho through the user id
    public function actionCarrinho($user_id)
    {

        $carrinhoModel = new $this->modelClass;
        $linhaCarrinhoModel = new $this->linhaCarrinhoModelClass;
        $produtoModel = new $this->produtoModelClass;

        $carrinho = $carrinhoModel::find()->where(['user_id' => $user_id])->one();

        if ($carrinho == null) {
            throw new \yii\web\NotFoundHttpException("Não existe um carrinho do user " . $user_id);
        }

        $linhaCarrinho = $linhaCarrinhoModel::find()->where(['carrinho_id' => $carrinho->id])->all();

        foreach ($linhasCarrinhosModel as $linhaCarrinhoModel) {
            $produto = $produtoModel::find()->where(['id' => $linhaCarrinho->produto_id])->one();
            $linhaCarrinho->produto_id = $produto;
        }

        return $carrinho;
    }

    public function actionUpdatecarrinho()
    {
        $carrinhoModel = new $this->modelClass;
        $userModel = new $this->userModelClass;
        $user_id = Yii::$app->request->post('user_id');
        $user = $userModel::find()->where(['id' => $user_id])->one();
        if ($user == null) {
            throw new \yii\web\NotFoundHttpException("Não existe o utilizador com o id " . $user_id);
        }

        $carrinho = $carrinhoModel::find()->where(['user_id' => $user_id])->one();
        if ($carrinho == null) {
            throw new \yii\web\NotFoundHttpException("Não existe um carrinho para o utilizador " . $user_id);
        }

        $carrinho->save();
        return $carrinho;
    }
}
