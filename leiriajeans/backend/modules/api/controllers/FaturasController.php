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
        // Modelos principais
        $faturasModel = new $this->modelClass;
        $linhasFaturasModel = new $this->linhasFaturasModelClass;

        // Busca a fatura pelo userdata_id
        $fatura = $faturasModel::find()->where(['userdata_id' => $id])->one();

        if ($fatura == null) {
            throw new \yii\web\NotFoundHttpException("Fatura com userdata_id {$id} não existe ou não foi encontrada.");
        }

        // Busca as linhas de fatura relacionadas
        $linhasFaturas = $linhasFaturasModel::find()->where(['fatura_id' => $fatura->id])->all();

        $resultArray = [];
        foreach ($linhasFaturas as $linha) {
            // Relacionamentos diretos
            $produto = $linha->produto; // Certifique-se de que a relação 'produto' está configurada no modelo LinhaFatura
            $iva = $linha->iva; // Certifique-se de que a relação 'iva' está configurada no modelo LinhaFatura

            // Montagem dos dados da linha
            $linhasInfo = [
                'nome_produto' => $produto ? $produto->nome : null,
                'valor' => $linha->precoVenda, // Preço de venda da linha
                'iva' => $iva ? $iva->percentagem : 0, // Percentual de IVA
                'valor_iva' => $linha->valorIva, // Valor do IVA da linha
                'quantidade' => $linha->quantidade, // Quantidade do produto
                'total' => $linha->subTotal, // Subtotal (preço + IVA * quantidade)
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


    public function actionFaturas($id)
    {
        // Model principal da tabela Fatura
        $faturasModel = new $this->modelClass;

        // Busca todas as faturas associadas ao userdata_id fornecido
        $faturas = $faturasModel::find()->where(['userdata_id' => $id])->all();

        // Verifica se há faturas para o userdata_id
        if (empty($faturas)) {
            throw new \yii\web\NotFoundHttpException("Nenhuma fatura encontrada para o utilizador com ID {$id}");
        }

        // Monta a resposta com os dados das faturas
        $resultArray = [];
        foreach ($faturas as $fatura) {
            $resultArray[] = [
                'id' => $fatura->id,
                'metodopagamento_id' => $fatura->metodopagamento_id,
                'metodoexpedicao_id' => $fatura->metodoexpedicao_id,
                'userdata_id' => $fatura->userdata_id,
                'data' => $fatura->data,
                'valorTotal' => $fatura->valorTotal,
                'statuspedido' => $fatura->statuspedido,
            ];
        }

        return $resultArray;
    }

}