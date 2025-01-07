<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <link rel="icon" href="<?= Yii::getAlias('@web/images/logo2.png') ?>" type="image/x-icon">
        <link href="<?= Yii::getAlias('@web/css/bootstrap.css') ?>" rel='stylesheet' type='text/css' />
        <link href="<?= Yii::getAlias('@web/css/style.css') ?>" rel='stylesheet' type='text/css' />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="<?= Yii::getAlias('@web/css/fwslider.css') ?>" media="all">


        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>


    </head>
    <body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header>
        <div class="header">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="header-left">
                            <div class="logo">
                                <a href="<?= Yii::$app->urlManager->createUrl(['site/index']) ?>"> <img src="<?= Yii::getAlias('@web/images/logo.png') ?>" alt=""/></a>
                            </div>
                            <div class="menu">
                                <a class="toggleMenu" href="#"><img src="<?= Yii::getAlias('@web/images/nav.png') ?>" alt="" /></a>
                                <ul class="nav" id="nav">
                                    <?php

                                    if (Yii::$app->user->isGuest) {?>
                                        <li><a<?php
                                            echo Html::tag(
                                                'li',
                                                Html::a('Login', ['/site/login'], ['class' => 'btn btn-link login text-decoration-none']),
                                                ['class' => 'd-flex']
                                            );
                                            ?></a></li>
                                        <li> <?php
                                            echo Html::tag(
                                                'li',
                                                Html::a('Registar', ['/site/signup'], ['class' => 'btn btn-link login text-decoration-none']),
                                                ['class' => 'd-flex']
                                            );
                                            ?></li>
                                    <?php }
                                    else {
                                        $userid = Yii::$app->user->id;
                                        echo Html::tag(
                                            'li',
                                            Html::beginForm(['user/view', 'id' => $userid], 'post', ['class' => 'd-flex'])
                                            . Html::submitButton(
                                                'Perfil (' . Yii::$app->user->identity->username . ')',
                                                ['class' => 'btn btn-link login text-decoration-none', 'style' => 'background:none; border:none; padding:0; cursor:pointer;']
                                            )
                                            . Html::endForm(),
                                            ['class' => 'd-flex']
                                        );
                                    }?>
                                    <li>  |    </li>

                                    <li><a href="<?= Yii::$app->urlManager->createUrl(['produtos/index']) ?>">Produtos</a></li>

                                    <li><a href="<?= Yii::$app->urlManager->createUrl(['faturas/index']) ?>">Faturas</a></li>

                                    <li><a href="<?= Yii::$app->urlManager->createUrl(['empresa/index']) ?>">Sobre Nós</a></li>



                                    <div class="clear"></div>
                                </ul>

                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="header_right">


                            <ul class="icon1 sub-icon1 profile_img">
                                <li>
                                    <?php if (!Yii::$app->user->isGuest): ?>
                                        <a class="active-icon c1" href="<?= Yii::$app->urlManager->createUrl(['carrinhos/index']) ?>"></a>
                                    <?php else: ?>
                                        <a class="active-icon c1" href="<?= Yii::$app->urlManager->createUrl(['site/login']) ?>"></a>
                                    <?php endif; ?>
                                </li>
                            </ul>

                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main role="main" class="flex-shrink-0">
        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <!--<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-start">&copy; <?php /*= Html::encode(Yii::$app->name) */?> <?php /*= date('Y') */?></p>
        <p class="float-end"><?php /*= Yii::powered() */?></p>
    </div>
    </footer>-->

    <footer>
        <div class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <ul class="footer_box">
                            <h4>Products</h4>
                            <li><a href="#">Homem</a></li>
                            <li><a href="#">Mulher</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <ul class="footer_box">
                            <h4>Sobre</h4>
                            <li><a href="#">Sobre Nós</a></li>
                            <li><a href="<?= Yii::$app->urlManager->createUrl(['site/team']) ?>">Team</a></li>
                            <li><a href="#">Ver Produtos</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <ul class="footer_box">
                            <h4>Suporte</h4>
                            <li><a href="#">Contacta-nos!</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <ul class="footer_box">
                            <h4>Newsletter</h4>
                            <div class="footer_search">
                                <form>
                                    <input type="text" value="Enter your email" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Enter your email';}">
                                    <input type="submit" value="Go">
                                </form>
                            </div>
                            <ul class="social">
                                <li class="facebook"><a href="https://www.facebook.com/politecnico.de.leiria/"><span> </span></a></li>
                                <li class="twitter"><a href="https://x.com/estg_ei"><span> </span></a></li>
                                <li class="instagram"><a href="https://www.instagram.com/politecnico_de_leiria/"><span> </span></a></li>
                            </ul>

                        </ul>
                    </div>
                </div>
                <div class="row footer_bottom">
                    <div class="copy">
                        <p>© 2024 Created by <atarget="_blank">LeiriaJeans</a></p>
                    </div>

                </div>
            </div>
        </div>
    </footer>

    <?php $this->endBody() ?>

    </body>


    </html>
<?php $this->endPage();