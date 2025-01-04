<?php
namespace backend\modules\api\controllers;
use app\mosquitto\phpMQTT;
use yii\base\Behavior;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii;
use yii\web\Controller;
use backend\modules\api\components\CustomAuth;
use carbon\carbon;

class FaturasController extends ActiveController
{
    public $modelClass = 'common\models\Fatura';
    public $userModelClass = 'common\models\User';
    public $carrinhosModelClass = 'common\models\Carrinho';
    public $linhaCarrinhoModelClass = 'common\models\LinhaCarrinho';
    public $linhasFaturasModelClass = 'common\models\LinhaFatura';
    public $metodoPagamentoModelClass = 'common\models\MetodoPagamento';
    public $metodoExpedicaoModelClass = 'common\models\MetodoExpedicao';


    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
        ];
        return $behaviors;
    }


    public function actionDados($id)
    {
        $faturasModel = new $this->modelClass;
        $linhasFaturasModel = new $this->linhasFaturasModelClass;
        $fatura = $faturasModel::find()->where(['id' => $id])->one();
        if($fatura == null){
            throw new \yii\web\NotFoundHttpException("Fatura não existe");
        }
        $linhasFaturas = $linhasFaturasModel::find()->where(['fatura_id'=>$fatura->id])->all();
        $resultArray = [];
        foreach($linhasFaturas as $linha){
            //METER OS NOSSOS DADOS NA ARRAY
            $linhasInfo = [
                'nome_produto' => $linha->linhasCarrinho->produto->nome,
                'valor'=> $linha->produtosCarrinhos->produto->preco,
                'iva'=> $linha->produtosCarrinhos->produto->iva->percentagem,
                'valor_iva' => $linha->produtosCarrinhos->produto->preco*($linha->produtosCarrinhos->produto->iva->percentagem/100),
                'quantidade' => $linha->produtosCarrinhos->quantidade,
                'total'=>($linha->produtosCarrinhos->produto->preco*($linha->produtosCarrinhos->produto->iva->percentagem/100)+$linha->produtosCarrinhos->produto->preco)*$linha->produtosCarrinhos->quantidade,
            ];
            $resultArray[] = $linhasInfo;
        }

        return $resultArray;
    }

    public function actionCriar()
    {
        if(!Yii::$app->request->isPost) {
            throw new \yii\web\NotFoundHttpException("O pedido tem de ser do tipo POST");
        }

        $requestPostFatura = \Yii::$app->request->post();

        $faturasModel = new $this->modelClass;
        $userModel = new $this->userModelClass;
        $carrinhosModel = new $this->carrinhosModelClass;
        $linhasFaturasModel = new $this->linhasFaturasModelClass;
        $linhasCarrinhoModel = new $this->linhaCarrinhoModelClass;

        $fatura = new $this->modelClass;
        $user = new $this->userModelClass;
        $carrinho = new $this->carrinhosModelClass;
        $linhasCarrinho = new $this->linhaCarrinhoModelClass;
        $linhasFaturas = new $this->linhasFaturasModelClass;
        $metodoPagamento = new $this->metodoPagamentoModelClass;
        $metodoExpedicao = new $this->metodoExpedicaoModelClass;

        $user = $userModel::find()->where(['id' => $requestPostFatura['user_id']])->one();
        $carrinho = $carrinhosModel::find()->where(['user_id' => $user->id])->one();
        $linhasCarrinho = $linhasCarrinhoModel::find()->where(['carrinho_id' => $carrinho->id])->all();


        $fatura->data = Carbon::now();
        $fatura->valorTotal = $carrinho->valortotal;
        $fatura->statuspedidos = 'Paga';
        $fatura->user_id = $user->id;
        $fatura->metodoPagamento_id = $metodoPagamento->id;
        $fatura->metodoExpedicao_id = $metodoExpedicao->id;
        $fatura->save();

        foreach ($linhasCarrinho as $linhaCarrinho) {
            $linhaFatura =  new $this->linhasFaturasModelClass;
            $linhaFatura->fatura_id = $fatura->id;
            $linhaFatura->linhacarrinho_id = $linhaCarrinho->id;
            $linhaFatura->save();
        }


        return $fatura;
    }

    public function FazPublishNoMosquitto($canal, $msg)
    {
        $server = "localhost";
        $port = 1883;
        $username = "";
        $password = "";
        $client_id = "phpMQTT-publisher";
        $mqtt = new phpMQTT($server, $port, $client_id);
        if ($mqtt->connect(true, NULL, $username, $password)) {
            $mqtt->publish($canal, $msg, 0);
            $mqtt->close();
        } else {
            file_put_contents("debug . output", "Time out!");
        }
    }

    public function actionDadosbyuser($user_id)
    {
        $userModel = new $this->userModelClass;
        $user = $userModel::find()->where(['id' => $user_id])->one();
        if($user == null){
            throw new \yii\web\NotFoundHttpException("Utilizador não existe");
        }
        $faturasModel = new $this->modelClass;
        $faturas = $faturasModel::find()->where(['user_id' => $user_id])->all();

        return $faturas;
    }
}
