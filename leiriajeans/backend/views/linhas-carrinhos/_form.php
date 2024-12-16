<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\LinhaCarrinho $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="linhas-carrinhos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'quantidade')->textInput() ?>

    <?= $form->field($model, 'precoVenda')->textInput() ?>

    <?= $form->field($model, 'valorIva')->textInput() ?>

    <?= $form->field($model, 'subTotal')->textInput() ?>

    <?= $form->field($model, 'carrinho_id')->textInput() ?>

    <?= $form->field($model, 'produto_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
