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
        <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
        <script src="<?= Yii::getAlias('@web/js/jquery.min.js') ?>"></script>
        <!--<script src="js/jquery.easydropdown.js"></script>-->
        <!--start slider -->
        <link rel="stylesheet" href="<?= Yii::getAlias('@web/css/fwslider.css') ?>" media="all">
        <script src="<?= Yii::getAlias('@web/js/jquery-ui.min.js') ?>"></script>
        <script src="<?= Yii::getAlias('@web/js/fwslider.js') ?>"></script>

        <!--end slider -->



        <script type="text/javascript">
            $(document).ready(function() {
                $(".dropdown img.flag").addClass("flagvisibility");

                $(".dropdown dt a").click(function() {
                    $(".dropdown dd ul").toggle();
                });

                $(".dropdown dd ul li a").click(function() {
                    var text = $(this).html();
                    $(".dropdown dt a span").html(text);
                    $(".dropdown dd ul").hide();
                    $("#result").html("Selected value is: " + getSelectedValue("sample"));
                });

                function getSelectedValue(id) {
                    return $("#" + id).find("dt a span.value").html();
                }

                $(document).bind('click', function(e) {
                    var $clicked = $(e.target);
                    if (! $clicked.parents().hasClass("dropdown"))
                        $(".dropdown dd ul").hide();
                });


                $("#flagSwitcher").click(function() {
                    $(".dropdown img.flag").toggleClass("flagvisibility");
                });
            });
        </script>
    </head>


    <body>

    <div class="banner">
        <!-- start slider -->
        <div id="fwslider">
            <div class="slider_container">
                <div class="slide">
                    <!-- Slide image -->
                    <img src="<?= Yii::getAlias('@web/images/slider1.jpg') ?>" class="img-responsive" alt=""/>
                    <!-- /Slide image -->
                    <!-- Texts container -->
                    <div class="slide_content">
                        <div class="slide_content_wrap">
                            <!-- Text title -->
                            <h1 class="title">Run Over<br>Everything</h1>
                            <!-- /Text title -->
                            <div class="button"><a href="#">See Details</a></div>
                        </div>
                    </div>
                    <!-- /Texts container -->
                </div>

                <div class="slide">
                    <img src="<?= Yii::getAlias('@web/images/slider2.jpg') ?>" class="img-responsive" alt=""/>
                    <div class="slide_content">
                        <div class="slide_content_wrap">
                            <h1 class="title">Run Over<br>Everything</h1>
                            <div class="button"><a href="#">See Details</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="timers"></div>
            <div class="slidePrev"><span></span></div>
            <div class="slideNext"><span></span></div>
        </div>
        <!--/slider -->
    </div>


    <div class="main">
        <div class="content-top">
            <h2>snowboards</h2>
            <p>hendrerit in vulputate velit esse molestie consequat, vel illum dolore</p>
            <div class="close_but"><i class="close1"> </i></div>
            <ul id="flexiselDemo3">
                <li><img src="<?= Yii::getAlias('@web/images/board1.jpg') ?>" class="img-responsive" alt=""/></li>
                <li><img src="<?= Yii::getAlias('@web/images/board2.jpg') ?>" class="img-responsive" alt=""/></li>
                <li><img src="<?= Yii::getAlias('@web/images/board3.jpg') ?>" class="img-responsive" alt=""/></li>
                <li><img src="<?= Yii::getAlias('@web/images/board4.jpg') ?>" class="img-responsive" alt=""/></li>
                <li><img src="<?= Yii::getAlias('@web/images/board5.jpg') ?>" class="img-responsive" alt=""/></li>
            </ul>
            <h3>SnowBoard Extreme Series</h3>
            <script type="text/javascript">
                $(window).load(function() {
                    $("#flexiselDemo3").flexisel({
                        visibleItems: 5,
                        animationSpeed: 1000,
                        autoPlay: true,
                        autoPlaySpeed: 3000,
                        pauseOnHover: true,
                        enableResponsiveBreakpoints: true,
                        responsiveBreakpoints: {
                            portrait: {
                                changePoint:480,
                                visibleItems: 1
                            },
                            landscape: {
                                changePoint:640,
                                visibleItems: 2
                            },
                            tablet: {
                                changePoint:768,
                                visibleItems: 3
                            }
                        }
                    });

                });
            </script>
            <script src="<?= Yii::getAlias('@web/js/jquery.flexisel.js') ?>"></script>
        </div>
    </div>

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
                    <div class="view view-ninth"><a href="single.html">
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

