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
        <link href="<?= Yii::getAlias('@web/css/style.css') ?>" rel='stylesheet' type='text/css' /><!--end slider -->




    </head>


    <body>


    <style>
        .content-top {
            position: relative;
            background-image: url('<?= Yii::getAlias('@web/images/banner.gif') ?>'); /* Caminho para a sua imagem de fundo */
            background-size: cover; /* Faz a imagem cobrir toda a área */
            background-position: center; /* Centraliza a imagem de fundo */
            padding: 50px; /* Espaçamento interno para o texto */
            color: white; /* Cor do texto */
        }


        /* Ajuste de estilo para o parágrafo */
        .content-top p {
            font-size: 20px; /* Tamanho do texto */
            font-style: italic; /* Torna o texto em itálico */
        }
    </style>

    <div class="main">
        <div class="content-top">
            <h2><strong>Bem Vindo a, </strong></h2>
            <p></p>
            <h2> LeiriaJeans</h2>
            <p>"Transformando visuais, sem complicações!"</p>
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
        <a href="<?= Url::to(['user/index']) ?>"> <!-- Gera a URL para user/index -->
         <img src="<?= Yii::getAlias('@web/images/roupamulher.jpg')?>" alt="Imagem 1" class="imagem"/>
        </a>
        <a href="<?= Url::to(['user/index']) ?>"> <!-- Gera a URL para user/index -->
        <img src="<?= Yii::getAlias('@web/images/polo1.webp')?>" alt="Imagem 2" class="imagem"/>
        </a>
    </div>

    <div class="features">
        <div class="container">
            <h3 class="m_3">Features</h3>
            <div class="close_but"><i class="close1"> </i></div>
            <div class="row">
                <div class="col-md-3 top_box">
                    <div class="view view-ninth"><a href="">
                            <img src="<?= Yii::getAlias('@web/images/jordancactus.webp') ?>" class="img-responsive" alt=""/>
                            <div class="mask mask-1"> </div>
                            <div class="mask mask-2"> </div>
                            <div class="content">
                                <h2>Sapatilhas</h2>
                                <p>Veste-te com LeiriaJeans</p>
                            </div>
                        </a> </div
                </div>
                <h4 class="m_4"><a href="#">Veste-te com a LeiriaJeans!</a></h4>
                <p class="m_5">Junta-te a nós!</p>
            </div>
            <div class="col-md-3 top_box">
                <div class="view view-ninth"><a href="single.html">
                        <img src="<?= Yii::getAlias('@web/images/nudeproject.webp') ?>" class="img-responsive" alt=""/>
                        <div class="mask mask-1"> </div>
                        <div class="mask mask-2"> </div>
                        <div class="content">
                            <h2>T-Shirts</h2>
                            <p>A melhor roupa na LeiriaJeans</p>
                        </div>
                    </a> </div>
                <h4 class="m_4"><a href="#">LeiriaJeans!</a></h4>

            </div>
            <div class="col-md-3 top_box">
                <div class="view view-ninth"><a href="single.html">
                        <img src="<?= Yii::getAlias('@web/images/calçacarhart.jpg') ?>" class="img-responsive" alt=""/>
                        <div class="mask mask-1"> </div>
                        <div class="mask mask-2"> </div>
                        <div class="content">
                            <h2>Calças</h2>
                            <p>Grande variedade de roupa</p>
                        </div>
                    </a> </div>
                <h4 class="m_4"><a href="#">Veste-te com a LeiriaJeans!</a></h4>
                <p class="m_5">Junta-te a nós!</p>
            </div>
            <div class="col-md-3 top_box1">
                <div class="view view-ninth"><a href="single.html">
                        <img src="<?= Yii::getAlias('@web/images/cap.jpg') ?>" class="img-responsive" alt=""/>
                        <div class="mask mask-1"> </div>
                        <div class="mask mask-2"> </div>
                        <div class="content">
                            <h2>LeiriaJeans Style #9</h2>
                            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing.</p>
                        </div>
                    </a> </div>
                <h4 class="m_4"><a href="#">Veste-te com a LeiriaJeans!</a></h4>
                <p class="m_5">Junta-te a nós!</p>
            </div>
        </div>
    </div>

    </body>

