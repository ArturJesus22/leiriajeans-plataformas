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

<link href="<?= Yii::getAlias('@web/css/style.css') ?>" rel='stylesheet' type='text/css' />
<h1><?= Html::encode($this->title) ?></h1>

<?php echo $this->render('_search', ['model' => $searchModel]); ?>

<div class="produtos-index">

    <div class="container">
        <div class="row">
            <?php foreach ($dataProvider->getModels() as $model): ?>
                <div class="col-md-3 product-item">
                    <?php
                    $imagem = $model->imagens ? $model->imagens[0] : null;
                    $imageUrl = $imagem ? Yii::getAlias('@web/images/produtos/' . $imagem->fileName) : Yii::getAlias('@web/images/default_product_image.jpg');
                    ?>
                    <div class="shop_box">
                        <a href="<?= Yii::$app->urlManager->createUrl(['produtos/view', 'id' => $model->id]) ?>">
                            <img src="<?= $imageUrl ?>" class="img-responsive product-image" alt="Imagem do produto"/>
                            <div class="shop_desc">
                                <h3 class="product-name"><?= Html::encode($model->nome) ?></h3>
                                <p class="product-description"><?= Html::encode($model->descricao) ?></p>
                                <span class="actual product-price"><?= Html::encode($model->preco) . '€' ?></span>
                                <ul class="buttons">
                                    <li class="shop_btn"><a href="/cart/add?id=<?= $model->id; ?>">Add To Cart</a></li>
                                    |
                                    <li class="shop_btn"><a href="<?= Yii::$app->urlManager->createUrl(['produtos/view', 'id' => $model->id]) ?>">Read More</a></li>
                                    <div class="clear"> </div>
                                </ul>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
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
