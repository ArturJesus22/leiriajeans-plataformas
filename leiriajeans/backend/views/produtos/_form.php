<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Ivas;
use common\models\Cores;
use common\models\Categorias;
use yii\helpers\ArrayHelper;
use common\models\Tamanhos;

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

    <?= $form->field($model, 'stock')->textInput() ?>

    <?= $form->field($model, 'tamanho_id')->dropDownList(ArrayHelper::map(Tamanhos::find()->all(),
        'id', 'nome'),
        ['prompt'=>'Selecione o Tamanho']) ?>

    <?= $form->field($model, 'cor_id')->dropDownList(ArrayHelper::map(Cores::find()->all(),
        'id', 'nome'),
        ['prompt'=>'Selecione a Cor']) ?>

    <?= $form->field($model, 'iva_id')->dropDownList(ArrayHelper::map(Ivas::find()->where(['status' => 1])->all(),
        'id', 'percentagem'),
        ['prompt' => 'Selecione o IVA']
    )?>

    <?= $form->field($model, 'categoria_id')->dropDownList(
        ArrayHelper::map(Categorias::find()->all(), 'id', function($model) {
            return $model->sexo . ' - ' . $model->tipo;
        }),
        ['prompt'=>'Selecione a Categoria']
    ) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
