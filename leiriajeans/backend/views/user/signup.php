<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var common\models\SignupForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use common\models\SignupForm;

$this->title = 'Registar Cliente';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-signup">

    <p>Regista aqui um cliente:</p>

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

            <?= $form->field($model, 'nome') ->label('Nome:')->textInput([
                'placeholder' => 'Nome'
            ]) ?>

            <?= $form->field($model, 'codigopostal') ->label('Código Postal:')->textInput([
                'placeholder' => 'Código Postal'
            ]) ?>

            <?= $form->field($model, 'localidade') ->label('Localidade:')->textInput([
                'placeholder' => 'Localidade'
            ]) ?>

            <?= $form->field($model, 'rua') ->label('Rua:')->textInput([
                'placeholder' => 'Rua'
            ]) ?>

            <?= $form->field($model, 'telefone') ->label('Telefone:')->textInput([
                'placeholder' => 'Telefone'
            ]) ?>

            <?= $form->field($model, 'nif') ->label('NIF:')->textInput([
                'placeholder' => 'NIF'
            ]) ?>



            <div class="form-group">
                <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
