<?php

use common\models\Carrinho;
use common\models\MetodoExpedicao;
use common\models\MetodoPagamento;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $carrinho Carrinho */
/* @var $metodosPagamento MetodoPagamento[] */
/* @var $metodosExpedicao MetodoExpedicao[] */
?>

<div class="checkout-container">
    <h1>Checkout</h1>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Produto</th>
                <th>Preço</th>
                <th>Quantidade</th>
                <th>Subtotal</th>
                <th>IVA</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (empty($linhasCarrinho)) { // Use $linhasCarrinho, que é a variável correta
                echo '<tr><td colspan="5">Nenhuma linha de fatura encontrada.</td></tr>';
            } else {
                foreach ($linhasCarrinho as $linha): // Use $linhasCarrinho aqui também
                    ?>
                    <tr>
                        <td>
                            <?= Html::encode($linha->produto ? $linha->produto->nome : 'Produto não encontrado') ?>
                        </td>
                        <td><?= Html::encode($linha['precoVenda']) . '€' ?></td>
                        <td><?= Html::encode($linha->quantidade) ?></td>
                        <td><?= Html::encode($linha['subTotal']) . '€' ?></td>
                        <td><?= Html::encode($linha['valorIva']) . '€' ?></td>
                    </tr>
                <?php endforeach;
            }
            ?>
            </tbody>
        </table>
    </div>

    <?php $form = ActiveForm::begin(); ?>

    <h3>Método de Pagamento</h3>
    <?= $form->field(new MetodoPagamento(), 'id')->radioList(
        \yii\helpers\ArrayHelper::map($metodosPagamento, 'id', 'nome'),
        ['name' => 'metodopagamento_id']
    )->label(false) ?>

    <h3>Método de Expedição</h3>
    <?= $form->field(new MetodoExpedicao(), 'id')->radioList(
        \yii\helpers\ArrayHelper::map($metodosExpedicao, 'id', 'nome'),
        ['name' => 'metodoexpedicao_id']
    )->label(false) ?>

    <div class="form-group">
        <?= Html::a('Confirmar', ['faturas/create-from-cart'], ['class' => 'btn btn-success', 'data-method' => 'post', 'data-confirm' => 'Tem certeza que deseja criar uma fatura a partir do carrinho?']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
