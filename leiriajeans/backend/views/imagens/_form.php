<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Produto;

/** @var yii\web\View $this */
/** @var common\models\Imagem $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="imagens-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'], // Essencial para enviar arquivos
    ]); ?>

    <?= $form->field($model, 'imageFiles[]')->fileInput(['multiple' => true]) ?>

    <?= $form->field($model, 'produto_id')->dropDownList(
        ArrayHelper::map(Produto::find()->all(), 'id', 'nome'),
        ['prompt' => 'Selecione um produto']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
