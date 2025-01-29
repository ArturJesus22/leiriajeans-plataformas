<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="faturas-form container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">Atualizar Estado da Entrega</h3>
        </div>

        <div class="card-body">
            <?php $form = ActiveForm::begin([
                'id' => 'update-form',
                'options' => ['class' => 'p-3']
            ]); ?>

            <?= $form->field($model, 'statusCompra')->dropDownList(
                [
                    'Enviado' => 'Enviado',
                    'Entregue' => 'Entregue',
                    'Em Processamento' => 'Em Processamento',
                ],
            )->label('Estado da Entrega', ['class' => 'form-label fs-5 mb-2']) ?>

            <div class="form-group text-center">
                <?= Html::submitButton('Guardar Alterações', [
                    'class' => 'btn btn-success btn-lg px-4',
                    'data' => ['confirm' => 'Tem certeza que deseja atualizar o estado do pedido?']
                ]) ?>

                <?= Html::a('Cancelar', ['view', 'id' => $model->id], [
                    'class' => 'btn btn-secondary btn-lg px-4 ms-2'
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
