<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\SignupForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to signup:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'username') ->label('Utilizador:')->textInput([
                    'autofocus' => true,
                    'placeholder' => 'Nome de Utilizador'
                    ]) ?>

                <?= $form->field($model, 'email') ->label('Email:')->textInput([
                    'placeholder' => 'Email'
                ]) ?>

                <?= $form->field($model, 'password') ->label('Password:')->passwordInput([
                    'placeholder' => 'Password'
                ]) ?>

                <?= $form->field($model, 'nome') ?>

                <?= $form->field($model, 'morada') ?>

                <?= $form->field($model, 'codigopostal') ?>

                <?= $form->field($model, 'localidade') ?>

                <?= $form->field($model, 'rua') ?>

                <?= $form->field($model, 'telefone') ?>

                <?= $form->field($model, 'nif') ?>



                <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
