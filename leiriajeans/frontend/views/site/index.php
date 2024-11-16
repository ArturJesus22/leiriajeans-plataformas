<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'LeiriaJeans';
?>

<!DOCTYPE html>
<html>

    <head>
        <title><?= Html::encode($this->title) ?></title>
        <link href="<?= Yii::getAlias('@web/css/bootstrap.css') ?>" rel='stylesheet' type='text/css' />
        <link href="<?= Yii::getAlias('@web/css/style.css') ?>" rel='stylesheet' type='text/css' />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="<?= Yii::getAlias('@web/css/fwslider.css') ?>" media="all">


        <!--end slider -->




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

    <div class="content-bottom">
        <div class="container">
            <div class="row content_bottom-text">
                <div class="col-md-7">
                    <h3>The Mountains<br>Snowboarding</h3>
                    <p class="m_1">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio.</p>
                    <p class="m_2">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="features">
        <div class="container">
            <h3 class="m_3">Features</h3>
            <div class="close_but"><i class="close1"> </i></div>
            <div class="row">
                <div class="col-md-3 top_box">
                    <div class="view view-ninth"><a href="">
                            <img src="<?= Yii::getAlias('@web/images/pic1.jpg') ?>" class="img-responsive" alt=""/>
                            <div class="mask mask-1"> </div>
                            <div class="mask mask-2"> </div>
                            <div class="content">
                                <h2>LeiriaJeans Style #9</h2>
                                <p>Lorem ipsum dolor sit amet, consectetuer adipiscing.</p>
                            </div>
                        </a> </div
                </div>
                <h4 class="m_4"><a href="#">Veste-te com a LeiriaJeans!</a></h4>
                <p class="m_5">Junta-te a nós!</p>
            </div>
            <div class="col-md-3 top_box">
                <div class="view view-ninth"><a href="single.html">
                        <img src="<?= Yii::getAlias('@web/images/pic2.jpg') ?>" class="img-responsive" alt=""/>
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
            <div class="col-md-3 top_box">
                <div class="view view-ninth"><a href="single.html">
                        <img src="<?= Yii::getAlias('@web/images/pic3.jpg') ?>" class="img-responsive" alt=""/>
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
            <div class="col-md-3 top_box1">
                <div class="view view-ninth"><a href="single.html">
                        <img src="<?= Yii::getAlias('@web/images/pic4.jpg') ?>" class="img-responsive" alt=""/>
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

