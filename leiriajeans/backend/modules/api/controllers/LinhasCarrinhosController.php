<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\web\Controller;
use yii\rest\ActiveController;
use \backend\modules\api\controllers\ProdutosController;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `api` module
 */
class LinhasCarrinhosController extends ActiveController
{

    public $modelClass = 'common\models\LinhaCarrinho';

    public $produtosModelClass = 'common\models\Produto';

    public $userModelClass = 'common\models\User';

    public $carrinhosModelClass = 'common\models\Carrinho';

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

    /**
     * @throws NotFoundHttpException
     */
    public function updateValorTotal($id)
    {
        $carrinhoModel = new $this->carrinhosModelClass;
        $carrinho = $carrinhoModel::find()->where(['id' => $id])->one();
        if ($carrinho == null) {
            throw new \yii\web\NotFoundHttpException("Não existe o carrinho com o id " . $id);
        }

        $linhasCarrinhoModel = new $this->modelClass;
        $linhasCarrinho = $linhasCarrinhoModel::find()->where(['carrinho_id' => $id])->all();

        // Atualiza o total mesmo que não existam linhas
        $carrinho->total = 0;
        if (!empty($linhasCarrinho)) {
            foreach ($linhasCarrinho as $linhaCarrinho) {
                $carrinho->total += $linhaCarrinho->subTotal;
            }
        }

        $carrinho->save();
    }


    public function actionDados($carrinho_id)
    {
        $carrinhoModel = new $this->carrinhosModelClass;
        $linhasCarrinhoModel = new $this->modelClass;
        $produtosModel = new $this->produtosModelClass;

        $carrinho = $carrinhoModel::find()->where(['id' => $carrinho_id])->one();
        if ($carrinho == null) {
            throw new \yii\web\NotFoundHttpException("Não existe um carrinho com o id " . $carrinho_id);
        }

        $linhasCarrinho = $linhasCarrinhoModel::find()->where(['carrinho_id' => $carrinho->id])->all();



        return $linhasCarrinho;
    }



    public function actionPostlinhacarrinho()
    {
        if (!Yii::$app->request->isPost) {
            throw new \yii\web\MethodNotAllowedHttpException("O pedido tem de ser do tipo POST");
        }

        // Obter dados do POST e garantir que são válidos
        $request = Yii::$app->request;
        $carrinho_id = $request->post('carrinho_id');
        $produto_id = $request->post('produto_id');
        $quantidade = $request->post('quantidade', 1); // default 1 se não especificado

        if (!$carrinho_id || !$produto_id) {
            throw new \yii\web\BadRequestHttpException("carrinho_id e produto_id são obrigatórios");
        }

        // Validar carrinho
        $carrinhoModel = new $this->carrinhosModelClass;
        $carrinho = $carrinhoModel::find()->where(['id' => $carrinho_id])->one();
        if (!$carrinho) {
            throw new \yii\web\NotFoundHttpException("Não existe o carrinho com o id " . $carrinho_id);
        }

        // Validar produto
        $produtosModel = new $this->produtosModelClass;
        $produto = $produtosModel::find()->where(['id' => $produto_id])->one();
        if (!$produto) {
            throw new \yii\web\NotFoundHttpException("Não existe o produto com o id " . $produto_id);
        }

        // Verificar se o produto já existe no carrinho
        $linhasCarrinhoModel = new $this->modelClass;
        $linhaExistente = $linhasCarrinhoModel::find()
            ->where(['carrinho_id' => $carrinho_id, 'produto_id' => $produto_id])
            ->one();

        if ($linhaExistente) {
            // Atualizar quantidade existente
            $linhaExistente->quantidade += $quantidade;
            $linhaExistente->subTotal = ($produto->preco + $linhaExistente->valorIva) * $linhaExistente->quantidade;
            if ($linhaExistente->save()) {
                $this->updateValorTotal($carrinho_id);
                return $linhaExistente;
            }
        } else {
            // Criar nova linha
            $linhaCarrinho = new $this->modelClass;

            // Calcular valor do IVA
            $valorIva = ($produto->preco * $produto->iva->percentagem) / 100;

            $linhaCarrinho->carrinho_id = $carrinho_id;
            $linhaCarrinho->produto_id = $produto_id;
            $linhaCarrinho->quantidade = $quantidade;
            $linhaCarrinho->precoVenda = $produto->preco;
            $linhaCarrinho->valorIva = $valorIva;
            $linhaCarrinho->subTotal = ($produto->preco + $valorIva) * $quantidade;

            if ($linhaCarrinho->save()) {
                $this->updateValorTotal($carrinho_id);
                return $linhaCarrinho;
            }
        }

        throw new \yii\web\ServerErrorHttpException("Erro ao guardar li66nha do carrinho");
    }

    public function actionUpdatelinhacarrinho($id)
    {
        $requestUpdateQuantidade = \Yii::$app->request->post();

        $linhasCarrinhoModel = new $this->modelClass;
        $linhasCarrinho = $linhasCarrinhoModel::find()->where(['id' => $id])->one();
        if($linhasCarrinho == null){
            throw new \yii\web\NotFoundHttpException("Não existe o produto n3o carrinho com o id " . $id);
        }
        $linhasCarrinho->quantidade = $requestUpdateQuantidade['quantidade'];
        $linhasCarrinho->subTotal = $linhasCarrinho->quantidade * ($linhasCarrinho->precoVenda+$linhasCarrinho->valorIva);
        $linhasCarrinho->save();
        $this->updateValorTotal($linhasCarrinho->carrinho_id);


        return $linhasCarrinho;
    }

    public function actionDeletelinhacarrinho($id)
    {
        $linhasCarrinhoModel = new $this->modelClass;
        $linhasCarrinho = $linhasCarrinhoModel::find()->where(['id' => $id])->one();

        if ($linhasCarrinho == null) {
            throw new \yii\web\NotFoundHttpException("Não existe o produto no carrinho com o id " . $id);
        }

        $carrinhoId = $linhasCarrinho->carrinho_id; // Armazena o carrinho_id antes de deletar
        $linhasCarrinho->delete();

        // Atualiza o valor total do carrinho
        $this->updateValorTotal($carrinhoId);

        // Retorna mensagem de sucesso
        return [
            'message' => 'Produto eliminado com sucesso do carrinho.',
            'id' => $id,
        ];
    }

}
