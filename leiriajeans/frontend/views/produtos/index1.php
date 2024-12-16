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


<div class="row">
    <div class="row shop_box-top">
    <div class="col-md-3 shop_box">
        <?php foreach ($dataProvider->getModels() as $model): ?>
            <div class="shop_desc">

                <h3><?= Html::encode($model->nome) ?></h3>
                <p><?= Html::encode($model->descricao) ?></p>
                <span><?= Html::encode($model->preco) ?> €</span><br>
                <ul class="buttons">
                    <li class="cart">
                        <?= Html::a('Adicionar ao Carrinho', '#', [
                            'class' => 'add-to-cart',
                            'data-product-id' => $model->id
                        ]) ?>
                    </li>
                    <li class="shop_btn">
                        <a href="<?= Url::to(['produtos/view', 'id' => $model->id]) ?>">Ver Produto</a>
                    </li>
                    <div class="clear"></div>
                </ul>
            </div>
            </a>
        <?php endforeach; ?>
    </div>
    </div>
</div>
    <div class="row">
    <div class="row shop_box-top">
        <div class="col-md-3 shop_box">
            <a href="single.html">
                <img src="images/pic5.jpg" class="img-responsive" alt=""/>

                <div class="shop_desc">
                    <h3><a href="#">aliquam volutp</a></h3>
                    <p>Lorem ipsum consectetuer adipiscing </p>
                    <span class="reducedfrom">$66.00</span>
                    <span class="actual">$12.00</span><br>
                    <ul class="buttons">
                        <li class="cart"><a href="#">Add To Cart</a></li>
                        <li class="shop_btn"><a href="#">Read More</a></li>
                        <div class="clear"> </div>
                    </ul>
                </div>
            </a></div>
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
