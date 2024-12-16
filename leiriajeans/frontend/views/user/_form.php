<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var yii\widgets\ActiveForm $form */
/** @var common\models\UsersForm $modelUserData */
?>

<div class="user-form">


    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'password_hash')->hiddenInput()->label(false) ?>


    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <!-- UserForm -->
    <?= $form->field($modelUserData, 'nome')->textInput() ?>

    <?= $form->field($modelUserData, 'codpostal')->textInput() ?>

    <?= $form->field($modelUserData, 'localidade')->textInput() ?>

    <?= $form->field($modelUserData, 'rua')->textInput() ?>

    <?= $form->field($modelUserData, 'nif')->textInput() ?>

    <?= $form->field($modelUserData, 'telefone')->textInput() ?>


    <?= $form->field($model, 'role')->textInput(['readonly' => true, 'value' => $model->role]) ?>



    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
