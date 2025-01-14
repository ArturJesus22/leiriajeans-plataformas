<?php
namespace backend\modules\api\controllers;


use Carbon\Carbon;
use common\models\Carrinho;
use yii\filters\auth\QueryParamAuth;
use yii\web\Controller;
use yii\rest\ActiveController;
use yii;
use yii\web\Response;

class CarrinhoController extends ActiveController
{
    public $modelClass = 'common\models\Carrinho';
    public $produtoModelClass = 'common\models\Produto';
    public $userModelClass = 'common\models\UserForm';
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

    public function actionCriar()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON; // Define o formato da resposta como JSON

        $model = new Carrinho(); // Crie uma nova instância do modelo Carrinho

        // Carrega os dados da requisição
        if ($model->load(\Yii::$app->request->post(), '') && $model->save()) {
            return $model; // Retorne o carrinho criado
        } else {
            // Adicione um log para verificar os dados recebidos
            \Yii::error("Erro ao criar carrinho: " . json_encode($model->getErrors()));
            return ['errors' => $model->getErrors()]; // Retorne os erros se falhar
        }
    }

    //function to get a carrinho through the user id
    public function actionCarrinho($user_id)
    {
        $carrinhoModel = new $this->modelClass;
        $linhasCarrinhoModel = new $this->linhaCarrinhoModelClass;
        $produtoModel = new $this->produtoModelClass;

        // Encontra o carrinho do utilizador
        $carrinho = $carrinhoModel::find()->where(['userdata_id' => $user_id])->one();

        if ($carrinho == null) {
            throw new \yii\web\NotFoundHttpException("Não existe um carrinho do user " . $user_id);
        }

        // Encontra as linhas do carrinho
        $linhasCarrinho = $linhasCarrinhoModel::find()->where(['carrinho_id' => $carrinho->id])->all();

        // Para cada linha do carrinho, encontra o produto relacionado
        foreach ($linhasCarrinho as $linhaCarrinho) {
            $produto = $produtoModel::find()->where(['id' => $linhaCarrinho->produto_id])->one();
            $linhaCarrinho->produto_id = $produto;
        }

        return $carrinho;  // Devolve o carrinho com as linhas e produtos
    }


    public function actionUpdatecarrinho()
    {
        $carrinhoModel = new $this->modelClass;
        $userModel = new $this->userModelClass;
        $user_id = Yii::$app->request->post('userdata_id');
        $user = $userModel::find()->where(['id' => $user_id])->one();
        if ($user == null) {
            throw new \yii\web\NotFoundHttpException("Não existe o utilizador com o id " . $user_id);
        }

        $carrinho = $carrinhoModel::find()->where(['userdata_id' => $user_id])->one();
        if ($carrinho == null) {
            throw new \yii\web\NotFoundHttpException("Não existe um carrinho para o utilizador " . $user_id);
        }

        $carrinho->save();
        return [$carrinho, $user_id];
    }

}
