<?php

use yii\helpers\Html;
use common\models\Tamanho;
use common\models\Cor;

/** @var yii\web\View $this */
/** @var common\Models\Produto $model */

$this->title = $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Produto', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="<?= Yii::getAlias('@web/css/style.css') ?>" rel="stylesheet">

<div class="produtos-view">
    <div class="main">
        <div class="shop_top">
            <div class="container">
                <div class="row">
                    <!-- Coluna com o carrossel de imagens -->
                    <div class="col-md-6">
                        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <?php foreach ($imagensAssociadas as $index => $imagem): ?>
                                    <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="<?= $index ?>"
                                            class="<?= $index === 0 ? 'active' : '' ?>" aria-current="<?= $index === 0 ? 'true' : '' ?>"
                                            aria-label="Slide <?= $index + 1 ?>"></button>
                                <?php endforeach; ?>
                            </div>

                            <div class="carousel-inner">
                                <?php foreach ($imagensAssociadas as $index => $imagem): ?>
                                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                        <img src="<?= Yii::getAlias('@web/images/produtos/' . $imagem->fileName) ?>"
                                             class="d-block w-100" alt="Imagem do produto">
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Anterior</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Próximo</span>
                            </button>
                        </div>
                    </div>

                    <!-- Coluna com a descrição, nome e botões -->
                    <div class="col-md-6">
                        <h3><?= Html::encode($model->nome) ?></h3>
                        <p class="m-3"><?= Html::encode($model->descricao) ?></p>

                        <h4 class="mt-4">Tamanho</h4>
                        <ul class="list-unstyled d-flex flex-wrap">
                            <?php foreach (Tamanho::find()->all() as $tamanho): ?>
                                <li class="me-2 mb-2">
                                    <button class="btn btn-outline-primary" data-tamanho-id="<?= $tamanho->id ?>">
                                        <?= Html::encode($tamanho->nome) ?>
                                    </button>
                                </li>
                            <?php endforeach; ?>
                        </ul>


                        <h4 class="mt-4">Sexo</h4>
                        <p>
                            <?= Html::encode($model->categoria ? $model->categoria->sexo : 'Não especificado') ?>
                        </p>

                        <h4 class="mt-4">Cores</h4>
                        <ul class="list-unstyled d-flex flex-wrap">
                            <?php foreach (Cor::find()->all() as $cor): ?>
                                <li class="me-2 mb-2">
                                    <button class="btn btn-outline-primary" data-cor-id="<?= $cor->id ?>">
                                        <?= Html::encode($cor->nome) ?>
                                    </button>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <div class="mt-4">
                            <p class="fw-bold fs-4">Preço: $130.25</p>
                            <div class="d-flex align-items-center mb-3">
                                <span class="me-2">Quantidade:</span>
                                <select class="form-select w-auto">
                                    <?php for ($i = 1; $i <= 6; $i++): ?>
                                        <option><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <span>Adicionar ao carrinho</span>
                            </button>
                        </div>
                    </div>
                </div> <!-- Fim da row -->
            </div>
        </div>
    </div>
</div>



<!--    --><?php /*= DetailView::widget([
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
    ]) */?>

</div>