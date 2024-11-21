<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Carrinhos $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="carrinhos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'userdata_id')->textInput() ?>

    <?= $form->field($model, 'produto_id')->textInput() ?>

    <?= $form->field($model, 'ivatotal')->textInput() ?>

    <?= $form->field($model, 'total')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
