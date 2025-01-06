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
        if($carrinho == null){
            throw new \yii\web\NotFoundHttpException("Não existe o carrinho com o id " . $id);
        }

        $linhasCarrinhoModel = new $this->modelClass;
        $linhasCarrinho = $linhasCarrinhoModel::find()->where(['carrinho_id' => $id])->all();
        if($linhasCarrinho == null){
            throw new \yii\web\NotFoundHttpException("Não existe produtos no carrinho com o id " . $id);
        }

        $carrinho->valortotal = 0;
        foreach ($linhasCarrinho as $linhaCarrinho) {
            $carrinho->valortotal += $linhaCarrinho->subtotal;
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

        if(!Yii::$app->request->isPost) {
            throw new \yii\web\NotFoundHttpException("O pedido tem de ser do tipo POST");
        }
        $requestPostLinhasCarrinho = \Yii::$app->request->post();
        $carrinhoModel = new $this->carrinhosModelClass;
        $idcarrinho = $requestPostLinhasCarrinho['carrinho_id'];

        $carrinho = $carrinhoModel::find()->where(['id' => $idcarrinho])->one();

        if($carrinho == null){
            throw new \yii\web\NotFoundHttpException("Não existe o carrinho com o id " . $idcarrinho);
        }

        $produtosModel = new $this->produtosModelClass;
        $produto = $produtosModel::find()->where(['id' => $requestPostLinhasCarrinho['produto_id']])->one();
        if($produto == null){
            throw new \yii\web\NotFoundHttpException("Não existe o produto com o id " . $requestPostLinhasCarrinho->produto_id);
        }
        $linhasCarrinhoNovo = new $this->modelClass;
        $prodCarrinho = $linhasCarrinhoNovo::find()->where(['carrinho_id'=>$idcarrinho,'produto_id'=>$requestPostLinhasCarrinho['produto_id']])->one();

        $linhasCarrinho = new $this->modelClass;
        if($prodCarrinho != null){



            $prodCarrinho->quantidade = strval($prodCarrinho->quantidade+1);
            $prodCarrinho->subtotal = ($produto->preco +  $prodCarrinho->valor_iva)*$prodCarrinho->quantidade;
            $prodCarrinho->save();
            $this->updateValorTotal($requestPostLinhasCarrinho['carrinho_id']);
        }else{

            $precoComIva = $produtosModel->getPrecoComIva($produto);
            $linhasCarrinho->carrinho_id = $idcarrinho;
            $linhasCarrinho->produto_id = $requestPostLinhasCarrinho['produto_id'];
            $linhasCarrinho->quantidade = $requestPostLinhasCarrinho['quantidade'];
            $linhasCarrinho->preco_venda = $produto->preco;
            $linhasCarrinho->valor_iva = $precoComIva - $produto->preco;
            $linhasCarrinho->subtotal = ($produto->preco +  $linhasCarrinho->valor_iva)*$linhasCarrinho->quantidade;
            $linhasCarrinho->save();
            $this->updateValorTotal($requestPostLinhasCarrinho['carrinho_id']);
        }



        return  $prodCarrinho;
    }

    public function actionUpdatelinhacarrinho($id)
    {
        $requestUpdateQuantidade = \Yii::$app->request->post();

        $linhasCarrinhoModel = new $this->modelClass;
        $linhasCarrinho = $linhasCarrinhoModel::find()->where(['id' => $id])->one();
        if($linhasCarrinho == null){
            throw new \yii\web\NotFoundHttpException("Não existe o produto no carrinho com o id " . $id);
        }
        $linhasCarrinho->quantidade = $requestUpdateQuantidade['quantidade'];
        $linhasCarrinho->subtotal = $linhasCarrinho->quantidade * ($linhasCarrinho->preco_venda+$linhasCarrinho->valor_iva);
        $linhasCarrinho->save();
        $this->updateValorTotal($linhasCarrinho->carrinho_id);


        return $linhasCarrinho;
    }

    public function actionDeletelinhacarrinho($id){
        $linhasCarrinhoModel = new $this->modelClass;
        $linhasCarrinho = $linhasCarrinhoModel::find()->where(['id' => $id])->one();
        if($linhasCarrinho == null){
            throw new \yii\web\NotFoundHttpException("Não existe o produto no carrinho com o id " . $id);
        }
        $linhasCarrinho->delete();
        $this->updateValorTotal($linhasCarrinho->carrinho_id);

        return 'Produto eliminado com sucesso do carrinho.';
    }

}



