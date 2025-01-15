<?php

use common\Models\Produto;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var frontend\Models\ProdutoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Produto';
$this->params['breadcrumbs'][] = $this->title;
?>
<link href="<?= Yii::getAlias('@web/css/style2.css') ?>" rel='stylesheet' type='text/css' />
<link href="<?= Yii::getAlias('@web/css/style.css') ?>" rel='stylesheet' type='text/css' />
<h1><?= Html::encode($this->title) ?></h1>

<?php echo $this->render('_search', ['model' => $searchModel]); ?>

<div class="filter-container">
    <form method="get" action="<?= Url::to(['produtos/index']) ?>">
        <h4>Filtrar por Gênero:</h4>
        <input type="hidden" name="tipo" value="<?= Yii::$app->request->get('tipo') ?>">
        <div class="form-group">
            <select name="sexo" class="form-control" onchange="this.form.submit()">
                <option value="">Todos</option>
                <?php foreach ($sexosDisponiveis as $sexo): ?>
                    <option value="<?= Html::encode($sexo->sexo) ?>" <?= Yii::$app->request->get('sexo') === $sexo->sexo ? 'selected' : '' ?>>
                        <?= Html::encode($sexo->sexo) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <form method="get" action="<?= Url::to(['produtos/index']) ?>">
        <h4>Filtrar por Tipo:</h4>
        <input type="hidden" name="sexo" value="<?= Yii::$app->request->get('sexo') ?>">
        <div class="form-group">
            <select name="tipo" class="form-control" onchange="this.form.submit()">
                <option value="">Todos</option>
                <?php foreach ($tiposDisponiveis as $tipo): ?>
                    <option value="<?= Html::encode($tipo->tipo) ?>" <?= Yii::$app->request->get('tipo') === $tipo->tipo ? 'selected' : '' ?>>
                        <?= Html::encode($tipo->tipo) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
</div>

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

                                    <?php if ($model->stock > 0): ?>
                                        <li class="shop_btn">
                                            <a href="<?= Yii::$app->urlManager->createUrl(['/carrinhos/add', 'produtos_id' => $model->id]) ?>"  id="addproduto">
                                                Adicionar
                                            </a>
                                        </li>
                                    <?php else: ?>
                                        <li class="shop_btn" style="background-color: grey;">
                                            <a href="<?= Yii::$app->urlManager->createUrl(['produtos/view', 'id' => $model->id]) ?>">
                                                Esgotado
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    |
                                    <li class="shop_btn">
                                        <a href="<?= Yii::$app->urlManager->createUrl(['produtos/view', 'id' => $model->id]) ?>">
                                            Ver
                                        </a>
                                    </li>
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

