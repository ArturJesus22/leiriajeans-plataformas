<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Iva;
use common\models\Cor;
use common\models\Categoria;
use yii\helpers\ArrayHelper;
use common\models\Tamanho;
use common\models\Produto;

/** @var yii\web\View $this */
/** @var common\models\Produto $model */
/** @var yii\widgets\ActiveForm $form */
/** @var common\models\Imagem $modelImagens */
?>

<div class="produtos-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descricao')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'preco')->textInput() ?>

    <?= $form->field($model, 'stock')->textInput() ?>

    <?= $form->field($model, 'cor_id')->dropDownList(ArrayHelper::map(Cor::find()->all(),
        'id', 'nome'),
        ['prompt'=>'Selecione a Cor']) ?>

    <?= $form->field($model, 'iva_id')->dropDownList(ArrayHelper::map(Iva::find()->where(['status' => 1])->all(),
        'id', 'percentagem'),
        ['prompt' => 'Selecione o IVA']
    )?>

    <?= $form->field($model, 'categoria_id')->dropDownList(
        ArrayHelper::map(Categoria::find()->all(), 'id', function($model) {
            return $model->sexo . ' - ' . $model->tipo;
        }),
        ['prompt'=>'Selecione a Categoria']
    ) ?>
    <div class="imagens-associadas">
        <h3>Imagens Associadas</h3>
        <div class="lista-imagens">
            <?php if (!empty($imagensAssociadas)): ?>
                <?php foreach ($imagensAssociadas as $imagem): ?>
                    <div class="imagem-item">
                        <img src="<?= Yii::getAlias('@web/public/imagens/produtos/' . $imagem->fileName) ?>"
                             alt="Imagem do produto" style="max-width: 500px; max-height: 500px;">
                        <p>Nome da Imagem: <?= Html::encode($imagem->fileName) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhuma imagem associada a este produto.</p>
            <?php endif; ?>
        </div>
    </div>

    <?= $form->field($modelImagens, 'imageFiles[]')->fileInput(['multiple' => true])->label('Associar Imagem:') ?>



    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
