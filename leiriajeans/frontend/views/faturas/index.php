<?php

use common\Models\Faturas;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

/**foreach ($dataProvider->getModels() as $model)*/

$this->title = 'Faturas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faturas-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <link href="<?= Yii::getAlias('@web/css/style2.css') ?>" rel='stylesheet' type='text/css' media="all" />
    <header>
        <div class="container-shop">
            <div class="navigation-shop">

            </div>
            <div class="notification-shop">
                Complete Your Purchase
            </div>
        </div>
    </header>
    <section class="content-shop">
        <div class="container-shop"></div>
<!--        <div class="details-shop shadow-shop">-->
<!--            <div class="details__item-shop">-->
<!---->
<!--                <div class="item__image-shop">-->
<!--                    <img class="iphone-shop" src="https://www.apple.com/v/iphone/compare/k/images/overview/compare_iphoneXSmax_silver_large.jpg" alt="">-->
<!--                </div>-->
<!--                <div class="item__details-shop">-->
<!--                    <div class="item__title-shop">-->
<!--                        iPhone X-->
<!--                    </div>-->
<!--                    <div class="item__price-shop">-->
<!--                        649,99 £-->
<!--                    </div>-->
<!--                    <div class="item__quantity-shop">-->
<!--                        Quantity: 1-->
<!--                    </div>-->
<!--                    <div class="item__description-shop">-->
<!--                        <ul>-->
<!--                            <li>Super fast and power efficient</li>-->
<!--                            <li>A lot of fast memory</li>-->
<!--                            <li>High resolution camera</li>-->
<!--                            <li>Smart tools for health and traveling and more</li>-->
<!--                            <li>Share your games and achievements with your friends via Group Play</li>-->
<!--                        </ul>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
        <div class="discount-shop"></div>

        <div class="container-shop">
            <div class="payment-shop">
                <div class="payment__title-shop">
                    Payment Method
                </div>
                <div class="payment__types-shop">
                    <?php if ($dataProvider->getCount() > 0): ?>
                        <?php foreach ($dataProvider->getModels() as $model): ?>
                            <div class="payment__type-shop payment__type--multibanco-shop">
                                <i class="icon-shop icon-multibanco-shop"></i>
                                <?php
                                // Supondo que o método de pagamento tenha um nome que você quer exibir
                                $metodoPagamento = $model->metodoPagamento; // Usa o relacionamento
                                ?>
                                <p><?= Html::encode($metodoPagamento->nome) ?> - <?= Html::encode($model->statuspedido) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Nenhum método de pagamento disponível.</p>
                    <?php endif; ?>
                    <div class="payment__type-shop payment__type--mbway-shop">
                        <i class="icon-shop icon-wallet-shop"></i>
                    </div>
                    <div class="payment__type-shop payment__type--paypal-shop">
                        <i class="icon-shop icon-paypal-shop"></i>
                    </div>
                </div>

                <div class="payment__info-shop">
                    <div class="payment-form credit-card-form hidden">
                        <form>
                            <div class="form__cc-shop">
                                <div class="row-shop">
                                    <div class="field-shop">
                                        <div class="title-shop">Credit Card Number</div>
                                        <input type="text" class="input-shop txt-shop text-validated-shop" value="4542 9931 9292 2293" />
                                    </div>
                                </div>
                                <div class="row-shop">
                                    <div class="field-shop small-shop">
                                        <div class="title-shop">Expiry Date</div>
                                        <select class="input-shop ddl-shop">
                                            <option value="01">01</option>
                                            <option value="02">02</option>
                                            <option value="03">03</option>
                                            <option value="04">04</option>
                                            <option value="05">05</option>
                                            <option value="06">06</option>
                                            <option value="07">07</option>
                                            <option value="08">08</option>
                                            <option value="09">09</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                        </select>
                                    </div>
                                    <div class="field-shop small-shop">
                                        <div class="title-shop">CVV<span class="numberCircle-shop"></span></div>
                                        <input type="text" class="input-shop txt-shop" />
                                    </div>
                                </div>
                                <div class="row-shop">
                                    <div class="field-shop">
                                        <div class="title-shop-cartao">Name on Card</div>
                                        <input type="text" class="input-shop txt-shop" />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="payment-form mbway-form hidden">
                        <form>
                            <div class="row-shop">
                                <div class="field-shop">
                                    <div class="title-shop">Número de telemovél</div>
                                    <input type="text" class="input-shop txt-shop-telemovel" placeholder="+351" />
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="payment-form paypal-form hidden">
                        <form>
                            <div class="row-shop">
                                <div class="field-shop">
                                    <div class="title-shop">Email</div>
                                    <input type="email" class="input-shop txt-shop-email" placeholder="Enter your PayPal email" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
        <div class="container-shop">
            <div class="actions-shop">
                <a href="#" class="btn-shop action__submit-shop">Finalizar compra <i class="icon-shop icon-arrow-right-circle-shop"></i></a>
                <a href="#" class="backBtn-shop">Volte para a loja</a>
            </div>
        </div>
    </section>

    <script src="../../web/js/faturas.js"></script>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'metodopagamento_id',
            'metodoexpedicao_id',
            'data',
            'valorTotal',
            //'statuspedido',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Faturas $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
