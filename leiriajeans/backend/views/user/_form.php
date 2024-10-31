<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var common\models\UsersForm $modelUserData */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput() ?>

    <?= $form->field($model, 'password_hash')->textInput() ?>

    <?= $form->field($model, 'email')->textInput() ?>

    <!-- UserForm -->
    <?= $form->field($modelUserData, 'nome')->textInput() ?>

    <?= $form->field($modelUserData, 'codpostal')->textInput() ?>

    <?= $form->field($modelUserData, 'localidade')->textInput() ?>

    <?= $form->field($modelUserData, 'rua')->textInput() ?>

    <?= $form->field($modelUserData, 'nif')->textInput() ?>

    <?= $form->field($modelUserData, 'telefone')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
