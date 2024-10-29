<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\FaturasSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="faturas-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'metodopagamento_id') ?>

    <?= $form->field($model, 'metodoexpedicao_id') ?>

    <?= $form->field($model, 'data') ?>

    <?= $form->field($model, 'valorTotal') ?>

    <?php // echo $form->field($model, 'statuspedido') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
