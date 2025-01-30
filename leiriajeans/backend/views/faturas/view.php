<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Pedido #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Faturas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

// Calcular totais
$subtotal = 0;
$totalIva = 0;
foreach ($linhasFatura as $linha) {
    $subtotal += $linha['subTotal'];
    $totalIva += $linha['valorIva'];
}
$total = $subtotal + $totalIva;
?>

<div class="fatura-view container py-5">
    <div class="card shadow-sm">
        <!-- Cabeçalho da Fatura -->
        <div class="card-header bg-primary text-white py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h3 class="mb-0"><?= Html::encode($this->title) ?></h3>
                    <p class="mb-0 mt-1 text-white-50">
                        Data: <?= Yii::$app->formatter->asDate($model->data, 'dd/MM/yyyy') ?>
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <span class="badge bg-<?= $model->statuspedido === 'pago' ? 'success' : ($model->statuspedido === 'pendente' ? 'warning' : 'danger') ?> p-2">
                        <?= ucfirst($model->statuspedido) ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            <!-- Informações do Pedido -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="border-start border-primary border-3 ps-3">
                        <h5 class="text-primary">Método de Pagamento</h5>
                        <p class="mb-0">
                            <i class="fas fa-credit-card me-2"></i>
                            <?= Html::encode($metodoPagamento ? $metodoPagamento->nome : 'Não especificado') ?>
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border-start border-primary border-3 ps-3">
                        <h5 class="text-primary">Método de Expedição</h5>
                        <p class="mb-0">
                            <i class="fas fa-truck me-2"></i>
                            <?= Html::encode($metodoExpedicao ? $metodoExpedicao->nome : 'Não especificado') ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Status de Entrega -->
            <div class="mb-4">
                <div class="progress" style="height: 25px;">
                    <?php
                    switch ($model->statusCompra) {
                        case 'Entregue':
                            $statusClass = 'bg-success';
                            $statusWidth = '100';
                            break;
                        case 'Em Processamento':
                            $statusClass = 'bg-danger';
                            $statusWidth = '30';
                            break;
                        default:
                            $statusClass = 'bg-warning';
                            $statusWidth = '50';
                    }
                    ?>
                    <div class="progress-bar <?= $statusClass ?>"
                         role="progressbar"
                         style="width: <?= $statusWidth ?>%"
                         aria-valuenow="<?= $statusWidth ?>"
                         aria-valuemin="0"
                         aria-valuemax="100">
                        <?= $model->statusCompra ?>
                    </div>
                </div>
            </div>

            <!-- Tabela de Produtos -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                    <tr>
                        <th>Produto</th>
                        <th class="text-center">Quantidade</th>
                        <th class="text-end">Preço Unit.</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($linhasFatura as $linha): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="ms-2">
                                        <h6 class="mb-0"><?= Html::encode($linha->produto ? $linha->produto->nome : 'Produto não encontrado') ?></h6>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center"><?= $linha->quantidade ?></td>
                            <td class="text-end"><?= number_format($linha->precoVenda, 2) ?>€</td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Resumo dos Valores -->
            <div class="row justify-content-end">
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <strong><?= number_format($subtotal, 2) ?>€</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>IVA Total:</span>
                                <strong><?= number_format($totalIva, 2) ?>€</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span class="h5">Total:</span>
                                <span class="h5 text-primary"><?= number_format($total, 2) ?>€</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rodapé da Fatura -->
        <div class="card-footer bg-light">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Para qualquer dúvida, entre em contato com o suporte da LeiriaJeans.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="text-center mt-4 mb-4">
    <?php if ($model->statusCompra === 'Em Processamento'): ?>
        <?= Html::a(
            '<i class="fas fa-paper-plane me-2"></i>Enviar',
            ['faturas/enviar', 'id' => $model->id],
            [
                'class' => 'btn btn-success btn-lg px-4 py-2 shadow-sm rounded-pill',
                'style' => '
                    background: linear-gradient(45deg, #28a745, #218838);
                    border: none;
                    transition: all 0.3s ease;
                    font-weight: 500;
                    letter-spacing: 0.5px;
                ',
                'data' => [
                    'confirm' => 'Tem certeza que deseja marcar este pedido como enviado?',
                    'method' => 'post',
                ],
                'onmouseover' => 'this.style.transform="translateY(-2px)"',
                'onmouseout' => 'this.style.transform="translateY(0)"'
            ]
        ) ?>
    <?php endif; ?>
</div>



<?php
$this->registerCss("
    @media print {
        .breadcrumb, .navbar, .footer {
            display: none;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
    .progress {
        border-radius: 15px;
    }
    .progress-bar {
        transition: width 1.5s ease-in-out;
    }
    .table td, .table th {
        vertical-align: middle;
    }
    .badge {
        font-size: 0.9em;
        padding: 8px 12px;
    }
");
?>
