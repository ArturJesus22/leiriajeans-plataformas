<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\MetodoExpedicao $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="metodos-expedicoes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descricao')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'custo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prazo_entrega')->textInput() ?>

    <?= $form->field($model, 'ativo')->dropDownList(
        [1 => 'Ativo', 0 => 'Desativo'],
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
