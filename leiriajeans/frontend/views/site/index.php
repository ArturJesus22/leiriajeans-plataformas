<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;
$this->title = 'LeiriaJeans';
?>

<!DOCTYPE html>
<html>

    <head>
        <title><?= Html::encode($this->title) ?></title>
        <link href="<?= Yii::getAlias('@web/css/bootstrap.css') ?>" rel='stylesheet' type='text/css' />
        <link href="<?= Yii::getAlias('@web/css/style.css') ?>" rel='stylesheet' type='text/css' />
        <link href="<?= Yii::getAlias('@web/css/style2.css') ?>" rel='stylesheet' type='text/css' />



    </head>


    <body>


    <style>
        .content-top {
            position: relative;
            background-image: url('<?= Yii::getAlias('@web/images/banner.gif') ?>');
            background-size: cover;
            background-position: center;
            padding: 50px;
            color: white;
        }



        .content-top p {
            font-size: 20px;
            font-style: italic;
        }
    </style>

    <div class="main">
        <div class="content-top">
            <h2><strong>Bem Vindo a, </strong></h2>
            <p></p>
            <h2> LeiriaJeans</h2>
            <p>"A transformar visuais, sem complicações!"</p>
        </div>
    </div>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <div class="imagens">
        <a href="<?= Yii::$app->urlManager->createUrl(['produtos/index', 'sexo' => 'Mulher']) ?>">
            <img src="<?= Yii::getAlias('@web/images/loira.webp') ?>" alt="Imagem Mulher" class="imagem"/>
        </a>

        <a href="<?= Yii::$app->urlManager->createUrl(['produtos/index', 'sexo' => 'Homem']) ?>">
            <img src="<?= Yii::getAlias('@web/images/loiro.webp') ?>" alt="Imagem Homem" class="imagem"/>
        </a>

    </div>

    <div class="features">
        <div class="container">
            <h3 class="m_3">Features</h3>
            <div class="close_but"><i class="close1"> </i></div>
            <div class="row">
                <div class="col-md-3 top_box">
                    <div class="view view-ninth"><a href="<?= Yii::$app->urlManager->createUrl(['produtos/index', 'tipo' => 'T-shirt']) ?>">
                            <img src="<?= Yii::getAlias('@web/images/nudeproject.webp') ?>" class="img-responsive" alt=""/>
                            <div class="mask mask-1"> </div>
                            <div class="mask mask-2"> </div>
                            <div class="content">
                                <h2>T-Shirts</h2>
                                <p>Veste-te com LeiriaJeans</p>
                            </div>
                        </a> </div
                </div>

            </div>
            <div class="col-md-3 top_box">
                <div class="view view-ninth"><a href="<?= Yii::$app->urlManager->createUrl(['produtos/index', 'tipo' => 'T-shirt']) ?>">
                        <img src="<?= Yii::getAlias('@web/images/nudeproject.webp') ?>" class="img-responsive" alt=""/>
                        <div class="mask mask-1"> </div>
                        <div class="mask mask-2"> </div>
                        <div class="content">
                            <h2>T-Shirts</h2>
                            <p>A melhor roupa na LeiriaJeans</p>
                        </div>
                    </a> </div>


            </div>
            <div class="col-md-3 top_box">
                <div class="view view-ninth"><a href="<?= Yii::$app->urlManager->createUrl(['produtos/index', 'tipo' => 'calças']) ?>">
                        <img src="<?= Yii::getAlias('@web/images/nudeproject.webp') ?>" class="img-responsive" alt=""/>
                        <div class="mask mask-1"> </div>
                        <div class="mask mask-2"> </div>
                        <div class="content">
                            <h2>T-Shirts</h2>
                            <p>Grande variedade de roupa</p>
                        </div>
                    </a> </div>


            </div>
            <div class="col-md-3 top_box1">
                <div class="view view-ninth"><a href="<?= Yii::$app->urlManager->createUrl(['produtos/index', 'tipo' => 'calças']) ?>">
                        <img src="<?= Yii::getAlias('@web/images/nudeproject.webp') ?>" class="img-responsive" alt=""/>
                        <div class="mask mask-1"> </div>
                        <div class="mask mask-2"> </div>
                        <div class="content">
                            <h2>T-Shirts</h2>
                            <p>Os melhores acessorios</p>
                        </div>
                    </a> </div>


            </div>
        </div>
    </div>

    </body>

