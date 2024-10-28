<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Cores;
use common\models\Ivas;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var common\models\Produtos $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="produtos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descricao')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'preco')->textInput() ?>

    <?= $form->field($model, 'sexo')->dropDownList([ 'M' => 'M', 'F' => 'F', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'tamanho')->dropDownList([ 'XXS' => 'XXS', 'XS' => 'XS', 'S' => 'S', 'M' => 'M', 'L' => 'L', 'XL' => 'XL', 'XXL' => 'XXL', ], ['prompt' => 'Escolha um tamanho']) ?>


    <?= $form->field($model, 'cor_id')
        ->label('Cor')
        ->dropDownList(Cores::find()->select(['nome', 'id'])
        ->indexBy('id')->column(), ['prompt' => 'Selecione uma cor']) ?>

    <?= $form->field($model, 'iva_id')->dropDownList(ArrayHelper::map(Ivas::find()->where(['status' => 1])->all(),
        'id', 'percentagem'),
        ['prompt' => 'Selecione o IVA']
    )?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
