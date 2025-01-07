<?php
namespace backend\modules\api\controllers;

use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

/**
 * Default controller for the `api` module
 */

class ProdutosController extends ActiveController
{
    public $modelClass = 'common\models\Produto';
    public $imagensModelClass = 'common\models\Imagem';
    public $categoriaModelClass = 'common\models\Categoria';
    public $ivaModelClass = 'common\models\Iva';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        return $behaviors;
    }


    //checkAccess

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        throw new \yii\web\NotFoundHttpException('Ação não permitida.');
    }

    public function actionProdutos()
    {
        $produtoModel = new $this->modelClass;
        $categoriaModel = new $this->categoriaModelClass;
        $ivaModel = new $this->ivaModelClass;
        $imagensModel = new $this->imagensModelClass;

        $produtos = $produtoModel::find()->all();

        if ($produtos == null) {
            throw new \yii\web\NotFoundHttpException("Não existem produtos");
        }

        $resultArray = [];

        foreach ($produtos as $produto) {
            // Procurar a categoria e IVA relacionados ao produto
            $categoria = $categoriaModel::find()->where(['id' => $produto->categoria_id])->one();
            $iva = $ivaModel::find()->where(['id' => $produto->iva_id])->one();

            // Obter a primeira imagem associada ao produto
            $imagem = $imagensModel::find()->where(['produto_id' => $produto->id])->one();

            // Caso o produto não tenha imagem, atribui uma imagem padrão
            if ($imagem == null) {
                $imagem = new $this->imagensModelClass;
                $imagem->fileName = "sem_imagem.jpg";  // Colocar uma imagem padrão
            }

            // Criar um array com as informações do produto
            $productInfo = [
                'id' => $produto->id,
                'nome' => $produto->nome,
                'preco' => $produto->preco,
                'descricao' => $produto->descricao,
                'iva' => $iva ? $iva->percentagem : null,  // Garantir que o IVA seja devolvido, se existir
                'categoria' => $categoria ? $categoria->sexo. ' - ' .$categoria->tipo : 'Categoria não encontrada', // Garantir que a categoria seja devolvida
                'imagens' => \Yii::getAlias('@web/images/produtos/' . $imagem->fileName), // Caminho completo da imagem
            ];

            // Adicionar as informações do produto ao array de resultados
            $resultArray[] = $productInfo;
        }

        return $resultArray;
    }

}
