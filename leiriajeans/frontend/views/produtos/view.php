<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Tamanhos;
use common\models\Cores;

/** @var yii\web\View $this */
/** @var common\Models\Produtos $model */

$this->title = $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Produtos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="produtos-view">
    <div class="main">
        <div class="shop_top">
            <div class="container">
                <div class="row">
                    <div class="col-md-9 single_left">
                        <div class="single_image">
                            <ul id="etalage">
                                <li>
                                    <a href="optionallink.html">
                                        <img class="etalage_thumb_image" src="images/3.jpg" />
                                        <img class="etalage_source_image" src="images/3.jpg" />
                                    </a>
                                </li>
                                <li>
                                    <img class="etalage_thumb_image" src="images/4.jpg" />
                                    <img class="etalage_source_image" src="images/4.jpg" />
                                </li>
                                <li>
                                    <img class="etalage_thumb_image" src="images/5.jpg" />
                                    <img class="etalage_source_image" src="images/5.jpg" />
                                </li>
                                <li>
                                    <img class="etalage_thumb_image" src="images/6.jpg" />
                                    <img class="etalage_source_image" src="images/6.jpg" />
                                </li>
                                <li>
                                    <img class="etalage_thumb_image" src="images/7.jpg" />
                                    <img class="etalage_source_image" src="images/7.jpg" />
                                </li>
                                <li>
                                    <img class="etalage_thumb_image" src="images/8.jpg" />
                                    <img class="etalage_source_image" src="images/8.jpg" />
                                </li>
                                <li>
                                    <img class="etalage_thumb_image" src="images/9.jpg" />
                                    <img class="etalage_source_image" src="images/9.jpg" />
                                </li>
                            </ul>
                        </div>

                        <div class="single_right">
                            <h3><?= $model->nome ?> </h3>
                            <p class="m_10"><?= $model->descricao ?></p>
                            <h4 class="m_12">Tamanho</h4>
                            <ul class="list-unstyled d-flex flex-wrap">
                                <?php foreach (Tamanhos::find()->all() as $tamanho): ?>
                                    <li class="mr-2 mb-2">
                                        <button class="btn btn-outline-primary tamanho-btn" data-tamanho-id="<?= $tamanho->id ?>">
                                            <?= Html::encode($tamanho->nome) ?>
                                        </button>
                                    </li>
                                <?php endforeach; ?>
                            </ul>

                            <ul class="product-colors">
                                <h3>Cores</h3>
                                <ul class="list-unstyled d-flex flex-wrap">
                                    <?php foreach (Cores::find()->all() as $cor): ?>
                                        <li class="mr-2 mb-2">
                                            <button class="btn btn-outline-primary tamanho-btn" data-cor-id="<?= $cor->id ?>">
                                                <?= Html::encode($cor->nome) ?>
                                            </button    >
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </ul>

                            <h4 class="m_12">Sexo</h4>
                            <p><?= Html::encode($model->sexo) ?></p>

                            <div class="btn_form">
                                <form>
                                    <input type="submit" value="buy now" title="">
                                </form>
                            </div>
                            <div class="social_buttons">
                                <h4>95 Items</h4>
                            </div>
                        </div>
                        <div class="clear"> </div>
                    </div>
                    <div class="col-md-3">
                        <div class="box-info-product">
                            <p class="price2">$130.25</p>
                            <ul class="prosuct-qty">
                                <span>Quantity:</span>
                                <select>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                    <option>6</option>
                                </select>
                            </ul>
                            <button type="submit" name="Submit" class="exclusive">
                                <span>Add to cart</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nome',
            'descricao:ntext',
            'preco',
            'sexo',
            'tamanho_id',
            'cor_id',
            'iva_id',
        ],
    ]) ?>

</div>
