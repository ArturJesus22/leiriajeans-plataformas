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
    <h1 class="text-center">Checkout</h1>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
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
            if (empty($linhasCarrinho)) {
                echo '<tr><td colspan="5" class="text-center">Nenhuma linha de fatura encontrada.</td></tr>';
            } else {
                foreach ($linhasCarrinho as $linha):
                    ?>
                    <tr>
                        <td><?= Html::encode($linha->produto ? $linha->produto->nome : 'Produto não encontrado') ?></td>
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

    <?php $form = ActiveForm::begin(['method' => 'post']); ?>

    <h3 class="mt-4">Método de Pagamento</h3>
    <?= $form->field(new MetodoPagamento(), 'id')->radioList(
        \yii\helpers\ArrayHelper::map($metodosPagamento, 'id', 'nome'),
        [
            'name' => 'metodopagamento_id',
            'id' => 'metodopagamento_id',
            'onchange' => 'mostrarCampos()',
            'itemOptions' => ['class' => 'form-check-input']
        ]
    )->label(false) ?>

    <div id="metodo-mbway" class="campo-metodo" style="display:none;">
        <?= Html::label('Número de Telemóvel', 'mbway-phone', ['class' => 'form-label']) ?>
        <?= Html::textInput('mbway-phone', '', ['id' => 'mbway-phone', 'class' => 'form-control', 'placeholder' => 'Número de Telemóvel']) ?>
    </div>

    <div id="metodo-paypal" class="campo-metodo" style="display:none;">
        <?= Html::label('Email', 'paypal-email', ['class' => 'form-label']) ?>
        <?= Html::textInput('paypal-email', '', ['id' => 'paypal-email', 'class' => 'form-control', 'placeholder' => 'Email']) ?>
    </div>

    <div id="metodo-multibanco" class="campo-metodo" style="display:none;">
        <?= Html::label('Nome', 'name', ['class' => 'form-label']) ?>
        <?= Html::textInput('name', '', ['id' => 'name', 'class' => 'form-control', 'placeholder' => 'Nome Completo']) ?>

        <?= Html::label('Número (16 dígitos)', 'multibanco-number', ['class' => 'form-label']) ?>
        <?= Html::textInput('multibanco-number', '', ['id' => 'multibanco-number', 'class' => 'form-control', 'maxlength' => 16, 'pattern' => '\d{16}', 'placeholder' => '16 dígitos']) ?>

        <?= Html::label('Validade (MM/AA)', 'expiry-date', ['class' => 'form-label']) ?>
        <?= Html::textInput('expiry-date', '', ['id' => 'expiry-date', 'class' => 'form-control', 'pattern' => '\d{2}/\d{2}', 'placeholder' => 'MM/AA']) ?>

        <?= Html::label('CVV', 'cvv', ['class' => 'form-label']) ?>
        <?= Html::textInput('cvv', '', ['id' => 'cvv', 'class' => 'form-control', 'pattern' => '\d{3}', 'maxlength' => 3, 'placeholder' => 'CVV (3 dígitos)']) ?>
    </div>

    <h3 class="mt-4">Método de Expedição</h3>
    <?= $form->field(new MetodoExpedicao(), 'id')->radioList(
        \yii\helpers\ArrayHelper::map($metodosExpedicao, 'id', 'nome'),
        ['name' => 'metodoexpedicao_id', 'itemOptions' => ['class' => 'form-check-input']]
    )->label(false) ?>

    <div class="form-group mt-4">
        <?= Html::a('Confirmar', ['faturas/create-from-cart'], ['class' => 'btn btn-success', 'data-method' => 'post', 'data-confirm' => 'Tem certeza que deseja criar uma fatura a partir do carrinho?']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<script>
    function mostrarCampos() {
        // Ocultar todos os campos
        document.getElementById('metodo-mbway').style.display = 'none';
        document.getElementById('metodo-paypal').style.display = 'none';
        document.getElementById('metodo-multibanco').style.display = 'none';

        // Recuperar o método de pagamento selecionado
        var metodoSelecionado = document.querySelector('input[name="metodopagamento_id"]:checked');

        if (metodoSelecionado) {
            var metodoId = metodoSelecionado.value;
            // Mostrar os campos dependendo do método selecionado
            if (metodoId == 1) { // MBWay
                document.getElementById('metodo-mbway').style.display = 'block';
            } else if (metodoId == 2) { // PayPal
                document.getElementById('metodo-paypal').style.display = 'block';
            } else if (metodoId == 3) { // Multibanco
                document.getElementById('metodo-multibanco').style.display = 'block';
            }
        }
    }

    // Chama a função para verificar se já existe um método selecionado ao carregar a página
    window.onload = function() {
        mostrarCampos();
    };
</script>
