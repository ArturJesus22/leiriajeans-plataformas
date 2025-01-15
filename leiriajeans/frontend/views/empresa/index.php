<?php

use common\Models\Empresa;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var common\models\Empresa $model */


$this->params['breadcrumbs'][] = $model->designacao;
?>
<div class="empresa-index">

    <h1><?= Html::encode($model->designacao) ?></h1>

        <div class="main">
            <div class="shop_top">
                <div class="container">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="map">
                                <iframe width="100%" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3068.2319995855323!2d-8.825914215627574!3d39.73443919822041!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd22735a4e067afb%3A0xcfaf619f4450fa76!2sPolit%C3%A9cnico%20de%20Leiria%20%7C%20ESTG%20-%20Escola%20Superior%20de%20Tecnologia%20e%20Gest%C3%A3o_Edif%C3%ADcio%20D!5e0!3m2!1sen!2spt!4v1730321087155!5m2!1sen!2spt" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe><br><small><a href="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3068.2319995855323!2d-8.825914215627574!3d39.73443919822041!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd22735a4e067afb%3A0xcfaf619f4450fa76!2sPolit%C3%A9cnico%20de%20Leiria%20%7C%20ESTG%20-%20Escola%20Superior%20de%20Tecnologia%20e%20Gest%C3%A3o_Edif%C3%ADcio%20D!5e0!3m2!1sen!2spt!4v1730321087155!5m2!1sen!2spt" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" style="color:#666;text-align:left;font-size:12px"></a></small>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <p class="m_8"><p><?= Html::encode($model->descricao) ?></p>
                            <div class="address">
                                <p><?= Html::encode($model->rua) ?></p>
                                <p><?= Html::encode($model->codigopostal) ?></p>
                                <p><?= Html::encode($model->localidade) ?></p>
                                <p>Tel: <?= Html::encode($model->telefone) ?></p>
                                <p>Email: <span><?= Html::encode($model->email) ?></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 contact">
                            <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>
                                <div class="alert alert-success">
                                    Agradeçemos o seu contacto, entraremos em contacto consigo dentro de 24 horas.
                                </div>
                            <?php endif; ?>
                            <?php $form = ActiveForm::begin(['action' => ['empresa/contact']]); ?>
                                <div class="to">
                                    <input type="text" class="text" name="nome" value="Nome" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Nome';}">
                                    <input type="text" class="text" name="email" value="Email" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Email';}">
                                    <input type="text" class="text" name="assunto" value="Assunto" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Assunto';}">
                                </div>
                                <div class="text">
                                    <textarea name="mensagem" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Mensagem';}">Mensagem:</textarea>
                                    <div class="form-submit">
                                        <input name="enviar" type="submit" id="enviar" value="Enviar"><br>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            <?php ActiveForm::end(); ?>
                            <div id="success-message" style="display:none;">
                                Agradeçemos o seu contacto, entraremos em contacto consigo dentro de 24 horas.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</div>

