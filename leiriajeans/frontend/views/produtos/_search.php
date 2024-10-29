<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var frontend\Models\ProdutosSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="produtos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'nome') ?>

    <?php // echo $form->field($model, 'tamanho_id') ?>

    <?php // echo $form->field($model, 'cor_id') ?>

    <?php // echo $form->field($model, 'iva_id') ?>

    <?php ActiveForm::end(); ?>

</div>
