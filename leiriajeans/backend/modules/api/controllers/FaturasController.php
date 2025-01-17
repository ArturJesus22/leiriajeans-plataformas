<?php
namespace backend\modules\api\controllers;
use app\mosquitto\phpMQTT;
use common\models\Carrinho;
use common\models\MetodoExpedicao;
use common\models\MetodoPagamento;
use yii\base\Behavior;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii;
use yii\web\Controller;
use backend\modules\api\components\CustomAuth;
use Carbon\Carbon;
use common\models\Fatura;
use common\models\User;
use common\models\UserForm;


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

        // Procura a fatura pelo userdata_id
        $fatura = $faturasModel::find()->where(['userdata_id' => $id])->one();

        if ($fatura == null) {
            throw new \yii\web\NotFoundHttpException("Fatura com userdata_id {$id} não existe ou não foi encontrada.");
        }

        // Procura as linhas de fatura relacionadas
        $linhasFaturas = $linhasFaturasModel::find()->where(['fatura_id' => $fatura->id])->all();

        $resultArray = [];
        foreach ($linhasFaturas as $linha) {
            // Relacionamentos diretos
            $produto = $linha->produto; // Certifica-se que a relação 'produto' está configurada no modelo LinhaFatura
            $iva = $linha->iva; // Certifica-se de que a relação 'iva' está configurada no modelo LinhaFatura

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
        $request = Yii::$app->request;
        $userdataId = $request->post('userdata_id'); // Mudado de get('user_id') para post('userdata_id')

        if (!$userdataId) {
            throw new \yii\web\BadRequestHttpException('O campo userdata_id é obrigatório.');
        }

        // Verificar se existe o userdata
        $userdata = UserForm::findOne($userdataId);
        if (!$userdata) {
            throw new \yii\web\NotFoundHttpException("UserData com ID {$userdataId} não encontrado.");
        }

        // Verificar se existe carrinho
        $carrinho = Carrinho::find()->where(['userdata_id' => $userdataId])->all();
        if (empty($carrinho)) {
            throw new \yii\web\NotFoundHttpException("Carrinho para o userdata_id {$userdataId} não encontrado.");
        }

        // Continuação do processo de criar a fatura
        $fatura = new Fatura();
        $fatura->valorTotal = $request->post('valorTotal');
        $fatura->data = date('Y-m-d H:i:s');
        $fatura->userdata_id = $userdataId;
        $fatura->metodopagamento_id = $request->post('metodopagamento_id');
        $fatura->metodoexpedicao_id = $request->post('metodoexpedicao_id');
        // Adicionar o status do pedido
        $fatura->statuspedido = strtolower($request->post('statuspedido', 'pendente')); // Convertido para minúsculo

        if ($fatura->save()) {
            return [
                'success' => true,
                'message' => 'Fatura criada com sucesso!',
                'data' => $fatura->toArray(),
            ];
        } else {
            return [
                'success' => false,
                'errors' => $fatura->errors,
            ];
        }
    }


    public function actionFaturas($id)
    {
        // Model principal da tabela Fatura
        $faturasModel = new $this->modelClass;

        // Procura todas as faturas associadas ao userdata_id fornecido
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