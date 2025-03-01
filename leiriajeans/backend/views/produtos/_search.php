<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\ProdutoSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="produtos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nome') ?>

    <?= $form->field($model, 'descricao') ?>

    <?= $form->field($model, 'preco') ?>

    <?php // echo $form->field($model, 'stock') ?>

    <?php // echo $form->field($model, 'tamanho_id') ?>

    <?php // echo $form->field($model, 'cor_id') ?>

    <?php // echo $form->field($model, 'iva_id') ?>

    <?php // echo $form->field($model, 'categoria_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
