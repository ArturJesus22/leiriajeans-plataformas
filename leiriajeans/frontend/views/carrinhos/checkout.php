<?php
use common\models\Carrinho;
use common\models\MetodoExpedicao;
use common\models\MetodoPagamento;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="checkout-container py-4">
    <div class="container">
        <h1 class="text-center mb-4">Checkout</h1>

        <?php $form = ActiveForm::begin([
            'action' => ['faturas/create-from-cart'],
            'method' => 'post',
            'options' => ['id' => 'checkout-form']
        ]); ?>

        <div class="row">
            <!-- Coluna da Esquerda -->
            <div class="col-md-8">
                <!-- Detalhes do Pedido -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Detalhes do Pedido</h4>
                        <div class="table-responsive">
                            <table class="table">
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
                                $total = 0;
                                $ivaTotal = 0;
                                if (empty($linhasCarrinho)) {
                                    echo '<tr><td colspan="5" class="text-center">Nenhum item no carrinho.</td></tr>';
                                } else {
                                    foreach ($linhasCarrinho as $linha):
                                        $total += $linha['subTotal'];
                                        $ivaTotal += $linha['valorIva'];
                                        ?>
                                        <tr>
                                            <td><?= Html::encode($linha->produto ? $linha->produto->nome : 'Produto não encontrado') ?></td>
                                            <td><?= Html::encode($linha['precoVenda']) ?>€</td>
                                            <td><?= Html::encode($linha->quantidade) ?></td>
                                            <td><?= Html::encode($linha['subTotal']) ?>€</td>
                                            <td><?= Html::encode($linha['valorIva']) ?>€</td>
                                        </tr>
                                    <?php endforeach;
                                }
                                $totalComIva = $total + $ivaTotal;
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Método de Pagamento -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Método de Pagamento</h4>
                        <?= $form->field(new MetodoPagamento(), 'id')->radioList(
                            \yii\helpers\ArrayHelper::map($metodosPagamento, 'id', 'nome'),
                            [
                                'name' => 'metodopagamento_id',
                                'id' => 'metodopagamento_id',
                                'onchange' => 'mostrarCampos()',
                                'itemOptions' => ['class' => 'form-check-input']
                            ]
                        )->label(false) ?>

                        <div id="metodo-mbway" class="campo-metodo mt-3" style="display:none;">
                            <?= Html::label('Número de Telemóvel', 'mbway-phone', ['class' => 'form-label']) ?>
                            <?= Html::textInput('mbway-phone', '', ['class' => 'form-control', 'placeholder' => 'Número de Telemóvel']) ?>
                        </div>

                        <div id="metodo-paypal" class="campo-metodo mt-3" style="display:none;">
                            <?= Html::label('Email PayPal', 'paypal-email', ['class' => 'form-label']) ?>
                            <?= Html::textInput('paypal-email', '', ['class' => 'form-control', 'placeholder' => 'Email']) ?>
                        </div>

                        <div id="metodo-multibanco" class="campo-metodo mt-3" style="display:none;">
                            <div class="alert alert-info">
                                <h5 class="alert-heading">Dados para Pagamento Multibanco</h5>
                                <hr>
                                <p class="mb-1"><strong>Entidade:</strong> 11111</p>
                                <p class="mb-1"><strong>Referência:</strong> <?= sprintf('%09d', rand(0, 999999999)) ?></p>
                                <p class="mb-1"><strong>Valor a Pagar:</strong> <?= number_format($totalComIva, 2) ?>€</p>
                                <small class="d-block mt-2">O pagamento deve ser efetuado no prazo de 24 horas.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Método de Expedição -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Método de Expedição</h4>
                        <?= $form->field(new MetodoExpedicao(), 'id')->radioList(
                            \yii\helpers\ArrayHelper::map($metodosExpedicao, 'id', 'nome'),
                            ['name' => 'metodoexpedicao_id', 'itemOptions' => ['class' => 'form-check-input']]
                        )->label(false) ?>
                    </div>
                </div>
            </div>

            <!-- Coluna da Direita - Resumo -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="card-title mb-0">Resumo do Pedido</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span><?= number_format($total, 2) ?>€</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>IVA</span>
                            <span><?= number_format($ivaTotal, 2) ?>€</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Total</strong>
                            <strong><?= number_format($totalComIva, 2) ?>€</strong>
                        </div>

                        <?= Html::submitButton('Confirmar Pedido', [
                            'class' => 'btn btn-primary btn-lg w-100',
                            'data' => ['confirm' => 'Confirmar a criação do pedido?']
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<script>
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        const metodoPagamento = document.querySelector('input[name="metodopagamento_id"]:checked');
        const metodoExpedicao = document.querySelector('input[name="metodoexpedicao_id"]:checked');

        if (!metodoPagamento) {
            e.preventDefault();
            alert('Por favor, selecione um método de pagamento.');
            return false;
        }

        if (!metodoExpedicao) {
            e.preventDefault();
            alert('Por favor, selecione um método de expedição.');
            return false;
        }

        return true;
    });

    function mostrarCampos() {
        const campos = ['metodo-mbway', 'metodo-paypal', 'metodo-multibanco'];
        campos.forEach(campo => {
            document.getElementById(campo).style.display = 'none';
        });

        const metodoSelecionado = document.querySelector('input[name="metodopagamento_id"]:checked');
        if (metodoSelecionado) {
            const metodoId = metodoSelecionado.value;
            if (metodoId == 1) {
                document.getElementById('metodo-mbway').style.display = 'block';
            } else if (metodoId == 2) {
                document.getElementById('metodo-paypal').style.display = 'block';
            } else if (metodoId == 3) {
                document.getElementById('metodo-multibanco').style.display = 'block';
            }
        }
    }

    window.onload = mostrarCampos;
</script>

<?php
$this->registerCss("
    .card {
        border: none;
        border-radius: 10px;
    }
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .alert-info {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
");
?>
