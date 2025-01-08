<?php

use yii\helpers\Html;
use common\models\Tamanho;
use common\models\Cor;
use common\models\Avaliacao;
use yii\widgets\ActiveForm;
use Carbon\Carbon;
use common\models\LinhaFatura;
use common\models\Fatura;

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
                        <hr>

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

                        <hr>
                        <h4 class="mt-4">Sexo</h4>
                        <p>
                            <?= Html::encode($model->categoria ? $model->categoria->sexo : 'Não especificado') ?>
                        </p>
                        <hr>

                        <h4 class="mt-4">Cor</h4>
                        <p>
                            <?= $model->cor ? Html::encode($model->cor->nome) : 'Não especificada' ?>
                        </p>


                        <hr>
                        <div class="mt-4">
                            <p class="fw-bold fs-4">Preço: <?= Html::encode($model->preco) . ' €'?></p>
                            <br>
                            <hr>
                            <p>Quantidade disponível: <?= Html::encode($model->stock) ?></p>
                            <hr>
                            <?php if ($model->stock > 0): ?>
                                <button class="shop_btn">
                                    <a href="<?= Yii::$app->urlManager->createUrl(['/carrinhos/add', 'produtos_id' => $model->id]) ?>" class="btn btn-primary">
                                        Adicionar ao carrinho
                                    </a>
                                </>
                            <?php else: ?>
                                <h5 class="shop_btn">
                                    <span class="btn btn-secondary">Esgotado</span>
                                </h5>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>



                <!-- Fim da row -->


                <?php
                $linhasFatura = LinhaFatura::find()->select('id')->where(['produto_id' => $model->id])->column();
                $avaliacoes = Avaliacao::find()->where(['linhafatura_id' => $linhasFatura])->all();

                ?>
                <!-- Seção de Avaliações -->
                <div class="produto-avaliacoes mt-4">
                    <h4>Avaliações do Produto</h4>
                    <div class="list-group mb-4">
                        <?php if ($avaliacoes): ?>
                            <?php foreach ($avaliacoes as $avaliacao): ?>
                                <div class="list-group-item">
                                    <h5>Avaliação de <?= Html::encode($avaliacao->user ? $avaliacao->user->username : 'Utilizador Desconhecido') ?></h5>
                                    <p>Comentário: <?= Html::encode($avaliacao->comentario) ?></p>
                                    <div class="rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span class="<?= $i <= $avaliacao->rating ? 'text-warning' : 'text-muted' ?>">&#9733;</span>
                                        <?php endfor; ?>
                                    </div>
                                    <p>Data da Avaliação: <?= Html::encode($avaliacao->data) ?></p>
                                    <hr>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">Ainda não há avaliações para este produto.</p>
                        <?php endif; ?>
                    </div>


                    <!-- Verificar se o utilizador pode avaliar -->
                    <?php if (!Yii::$app->user->isGuest): ?>
                        <?php
                        // Verificar se o utilizador comprou o produto
                        $userId = Yii::$app->user->identity->userform->id;
                        // Obter todas as faturas do utilizador
                        $faturas = Fatura::find()->where(['userdata_id' => $userId])->all();

                        $linhaFatura = null;

                        // Verificar se o produto está em alguma linha de fatura
                        foreach ($faturas as $fatura) {
                            $linhaFatura = LinhaFatura::find()
                                ->where(['produto_id' => $model->id, 'fatura_id' => $fatura->id])
                                ->one();

                            if ($linhaFatura) {
                                break; // Produto encontrado, parar a procura.
                            }
                        }


                        $avaliacaoModel = new Avaliacao();

                        ?>

                        <?php if ($linhaFatura): ?>
                            <?php $form = ActiveForm::begin(['action' => ['avaliacoes/create'], 'method' => 'post']); ?>
                            <div class="mt-4">
                                <h5>Deixe sua Avaliação</h5>

                                <?= $form->field($avaliacaoModel, 'comentario')->textarea([
                                    'class' => 'form-control',
                                    'placeholder' => 'Escreva sua avaliação aqui...',
                                    'rows' => 4
                                ])->label(false); ?>

                                <div class="mt-3">
                                    <label>Classificação:</label>
                                    <div class="rating" id="starRating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span class="star" data-value="<?= $i ?>">&#9733;</span>
                                        <?php endfor; ?>
                                    </div>
                                    <?= $form->field($avaliacaoModel, 'rating')->hiddenInput(['id' => 'ratingInput'])->label(false); ?>
                                </div>
                                <?= $form->field($avaliacaoModel, 'userdata_id')->hiddenInput(['value' => Yii::$app->user->identity->userform->id])->label(false); ?>

                                <?= $form->field($avaliacaoModel, 'data')->hiddenInput(['value' => Carbon::now()->toDateTimeString()])->label(false); ?>

                                <?= $form->field($avaliacaoModel, 'linhafatura_id')->hiddenInput(['value' => $linhaFatura->id])->label(false); ?>

                                <?= Html::submitButton('Enviar Avaliação', ['class' => 'btn btn-primary mt-3']); ?>

                            </div>
                            <?php ActiveForm::end(); ?>
                        <?php else: ?>
                            <p class="text-danger">Você precisa comprar este produto antes de avaliá-lo.</p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-muted">Faça login para deixar uma avaliação.</p>
                    <?php endif; ?>
                </div>
            </div>



            <!-- Estilo para as estrelas -->
            <style>
                .rating .star {
                    font-size: 24px;
                    color: #ccc;
                    cursor: pointer;
                    transition: color 0.3s;
                }

                .rating .star.selected,
                .rating .star:hover {
                    color: #f39c12;
                }
            </style>

            <!-- Script para interatividade das estrelas -->
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const stars = document.querySelectorAll('#starRating .star');
                    const ratingInput = document.getElementById('ratingInput');

                    stars.forEach((star) => {
                        star.addEventListener('click', function () {
                            const value = this.getAttribute('data-value');
                            ratingInput.value = value;

                            // Atualiza a aparência das estrelas
                            stars.forEach(s => s.classList.remove('selected'));
                            for (let i = 0; i < value; i++) {
                                stars[i].classList.add('selected');
                            }
                        });
                    });
                });
            </script>



        </div>
    </div>
</div>
</div>
</div>