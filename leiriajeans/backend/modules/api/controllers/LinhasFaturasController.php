<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class LinhasFaturasController extends ActiveController
{
    public $modelClass = 'common\models\LinhaFatura';
    public $faturaModelClass = 'common\models\Fatura';
    public $produtoModelClass = 'common\models\Produto';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
        ];
        return $behaviors;
    }

    /**
     * Obter todas as linhas de fatura associadas a uma fatura específica.
     * @param int $fatura_id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionDados($fatura_id)
    {
        $faturaModel = new $this->faturaModelClass;
        $linhaFaturaModel = new $this->modelClass;

        $fatura = $faturaModel::findOne(['id' => $fatura_id]);
        if (!$fatura) {
            throw new NotFoundHttpException("Fatura com o ID {$fatura_id} não encontrada.");
        }

        $linhasFatura = $linhaFaturaModel::find()->where(['fatura_id' => $fatura_id])->all();
        return $linhasFatura;
    }

    /**
     * Criar uma nova linha de fatura associada a uma fatura específica.
     * @return array
     * @throws ServerErrorHttpException
     */
    public function actionCriarlinhafatura()
    {
        if (!Yii::$app->request->isPost) {
            throw new \yii\web\MethodNotAllowedHttpException("O pedido tem de ser do tipo POST.");
        }

        $data = Yii::$app->request->post();

        $fatura_id = $data['fatura_id'] ?? null;
        if (!$fatura_id) {
            throw new \yii\web\BadRequestHttpException("O parâmetro 'fatura_id' é obrigatório.");
        }

        $produto_id = $data['produto_id'] ?? null;
        $quantidade = $data['quantidade'] ?? 1;
        $precoVenda = $data['precoVenda'] ?? 0;
        $valorIva = $data['valorIva'] ?? 0;
        $subTotal = $data['subTotal'] ?? ($precoVenda * $quantidade + $valorIva);

        $faturaModel = new $this->faturaModelClass;
        $fatura = $faturaModel::findOne(['id' => $fatura_id]);
        if (!$fatura) {
            throw new NotFoundHttpException("Fatura com o ID {$fatura_id} não encontrada.");
        }

        $linha = new $this->modelClass();
        $linha->fatura_id = $fatura_id;
        $linha->produto_id = $produto_id;
        $linha->quantidade = $quantidade;
        $linha->precoVenda = $precoVenda;
        $linha->valorIva = $valorIva;
        $linha->subTotal = $subTotal;

        if (!$linha->save()) {
            throw new ServerErrorHttpException("Erro ao criar linha de fatura: " . json_encode($linha->getErrors()));
        }

        return [
            'success' => true,
            'created' => $linha,
        ];
    }
}
