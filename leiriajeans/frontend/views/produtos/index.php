<?php

use common\Models\Produtos;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var frontend\Models\ProdutosSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Produtos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="produtos-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="row">
        <?php foreach ($dataProvider->getModels() as $model): ?>
            <div class="main">
                <div class="shop_top">
                    <div class="container">
                        <div class="row shop_box-top">
                            <div class="col-md-3 shop_box">
                                <?php
                                // Obter a primeira imagem associada ao produto
                                $imagem = $model->imagens ? $model->imagens[0] : null;
                                // Verifique se a imagem existe e tenha um caminho válido
                                $imageUrl = $imagem ? Yii::getAlias('@web/images/produtos/' . $imagem->fileName) : Yii::getAlias('@web/images/default_product_image.jpg');
                                ?>
                                <a href="<?= Yii::$app->urlManager->createUrl(['produtos/view', 'id' => $model->id]) ?>">
                                    <img src="<?= $imageUrl ?>" class="img-responsive" alt="Imagem do produto"/>
                                    <div class="shop_desc">
                                        <h3><?= Html::encode($model->nome) ?></h3>
                                        <p><?= Html::encode($model->descricao) ?></p>
                                        <span class="actual"><?= Html::encode($model->preco) ?></span>
                                        <ul class="buttons">
                                            <li class="cart">
                                                <?= Html::a('Adicionar ao Carrinho', '#', [
                                                    'class' => 'add-to-cart btn btn-primary',
                                                    'data-product-id' => $model->id,
                                                    'onclick' => 'console.log("ID do produto clicado:", $(this).data("product-id"));'
                                                ]) ?>
                                            </li>
                                            <li class="shop_btn">
                                                <a href="<?= Yii::$app->urlManager->createUrl(['produtos/view', 'id' => $model->id]) ?>">Veja mais</a>
                                            </li>
                                        </ul>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<?php
$this->registerJs("
    $('.add-to-cart').on('click', function(e) {
        e.preventDefault();
        var productId = $(this).data('product-id');

        $.ajax({
            url: '" . Yii::$app->urlManager->createUrl(['/carrinhos/add']) . "',
            method: 'POST',
            data: { 
                id: productId,
                _csrf: '" . Yii::$app->request->csrfToken . "'
            },
            dataType: 'json',
            success: function(response) {
                alert(response.success ? response.message : 'Erro: ' + response.message);
            },
            error: function() {
                alert('Erro ao adicionar o produto ao carrinho. Por favor, tente novamente.');
            }
        });
    });
");
?>
