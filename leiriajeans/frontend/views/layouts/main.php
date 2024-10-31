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
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <script src="<?= Yii::getAlias('@web/js/jquery.min.js') ?>"></script>

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
                            <a class="toggleMenu" href="#"><img src="images/nav.png" alt="" /></a>
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
                                    echo Html::tag(
                                        'li',
                                        Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
                                        . Html::submitButton(
                                            'Logout (' . Yii::$app->user->identity->username . ')',
                                            ['class' => 'btn btn-link login text-decoration-none', 'style' => 'background:none; border:none; padding:0; cursor:pointer;']
                                        )
                                        . Html::endForm(),
                                        ['class' => 'd-flex']
                                    );
                                }?>
                                <li>  |    </li>

                                <li><a href="<?= Yii::$app->urlManager->createUrl(['produtos/index']) ?>">Produtos</a></li>
                                <li><a href="<?= Yii::$app->urlManager->createUrl(['empresa/index']) ?>">Sobre Nós</a></li>
                                <li><a href="<?= Yii::$app->urlManager->createUrl(['site/contact']) ?>">Contactos</a></li>



                                <div class="clear"></div>
                            </ul>
                            <script type="text/javascript" src="js/responsive-nav.js"></script>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="header_right">

                        <!-- start search-->
                        <div class="search-box">
                            <div id="sb-search" class="sb-search">
                                <form>
                                    <input class="sb-search-input" placeholder="Pesquisar produtos..." type="search" name="search" id="search">
                                    <input class="sb-search-submit" type="submit" value="">
                                    <span class="sb-icon-search"> </span>
                                </form>
                            </div>
                        </div>
                        <!----search-scripts---->
                        <script src="js/classie.js"></script>
                        <script src="js/uisearch.js"></script>
                        <script>
                            new UISearch( document.getElementById( 'sb-search' ) );
                        </script>


                        <!----//search-scripts---->
                        <ul class="icon1 sub-icon1 profile_img">
                            <li><a class="active-icon c1" href="#"> </a>
                                <ul class="sub-icon1 list">
                                    <div class="clear"></div>
                                    <li class="list_img"><img src="images/1.jpg" alt=""/></li>
                                    <li class="list_desc"><h4><a href="#">velit esse molestie</a></h4><span class="actual">1 x$12.00</span></li>
                                    <div class="login_buttons">
                                        <div class="check_button"><a href="<?= Yii::$app->urlManager->createUrl(['faturas/index']) ?>">Check out</a></div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="clear"></div>
                                </ul>
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
                        <li><a href="#">Mens</a></li>
                        <li><a href="#">Womens</a></li>
                        <li><a href="#">Youth</a></li>
                       </ul>
                </div>
                <div class="col-md-3">
                    <ul class="footer_box">
                        <h4>About</h4>
                        <li><a href="#">Careers and internships</a></li>
                        <li><a href="#">Sponserships</a></li>
                        <li><a href="#">team</a></li>
                        <li><a href="#">Catalog Request/Download</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <ul class="footer_box">
                        <h4>Customer Support</h4>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Shipping and Order Tracking</a></li>
                        <li><a href="#">Easy Returns</a></li>
                        <li><a href="#">Warranty</a></li>
                        <li><a href="#">Replacement Binding Parts</a></li>
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
                            <li class="facebook"><a href="#"><span> </span></a></li>
                            <li class="twitter"><a href="#"><span> </span></a></li>
                            <li class="instagram"><a href="#"><span> </span></a></li>
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
