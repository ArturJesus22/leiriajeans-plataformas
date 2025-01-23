<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Os meus Pedidos';
$this->params['breadcrumbs'][] = $this->title;

// Adicione estes estilos CSS no início do arquivo ou em um arquivo CSS separado
$this->registerCss("
    .order-card {
        transition: transform 0.2s;
        border: none;
        border-radius: 15px;
    }
    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2) !important;
    }
    .order-header {
        background: linear-gradient(45deg, #2c3e50, #3498db);
        border-radius: 15px 15px 0 0;
        padding: 20px;
    }
    .order-id {
        font-size: 1.2em;
        font-weight: 600;
        margin: 0;
    }
    .order-date {
        opacity: 0.8;
        font-size: 0.9em;
    }
    .status-badge {
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 0.85em;
        font-weight: 500;
    }
    .status-pendente { background-color: #ffd700; color: #000; }
    .status-pago { background-color: #28a745; color: #fff; }
    .status-anulada { background-color: #dc3545; color: #fff; }
    .status-entregue { background-color: #17a2b8; color: #fff; }
    .order-table {
        margin-top: 1rem;
    }
    .order-table th {
        background-color: #f8f9fa;
        border-top: none;
    }
    .order-info {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-top: 20px;
    }
    .order-info-item {
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }
    .order-info-label {
        font-weight: 600;
        margin-right: 10px;
        color: #6c757d;
    }
    .action-buttons {
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 0 0 15px 15px;
    }
    .btn-view {
        background-color: #3498db;
        border-color: #3498db;
        padding: 8px 20px;
        margin-right: 10px;
    }
    .btn-confirm {
        background-color: #2ecc71;
        border-color: #2ecc71;
        padding: 8px 20px;
    }
");
?>

<div class="faturas-index container py-5">
    <h1 class="display-4 mb-5 text-center"><?= Html::encode($this->title) ?></h1>

    <?php if (!empty($dataProvider->models)): ?>
        <div class="row">
            <?php foreach ($dataProvider->models as $fatura): ?>
                <div class="col-12 mb-4">
                    <div class="order-card shadow">
                        <div class="order-header text-white d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="order-id">Pedido #<?= $fatura->id ?></h3>
                                <div class="order-date">
                                    <i class="far fa-calendar-alt mr-2"></i>
                                    <?= Yii::$app->formatter->asDate($fatura->data, 'dd/MM/yyyy') ?>
                                </div>
                            </div>
                            <span class="status-badge status-<?= strtolower($fatura->statuspedido) ?>">
                                <?= ucfirst($fatura->statuspedido) ?>
                            </span>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table order-table">
                                    <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th class="text-center">Quantidade</th>
                                        <th class="text-right">Preço</th>
                                        <th class="text-right">IVA</th>
                                        <th class="text-right">Subtotal</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($fatura->linhafaturas as $linhafatura): ?>
                                        <tr>
                                            <td><?= Html::encode($linhafatura->produto->nome) ?></td>
                                            <td class="text-center"><?= $linhafatura->quantidade ?></td>
                                            <td class="text-right"><?= number_format($linhafatura->precoVenda, 2) ?>€</td>
                                            <td class="text-right"><?= number_format($linhafatura->valorIva, 2) ?>€</td>
                                            <td class="text-right"><?= number_format($linhafatura->subTotal, 2) ?>€</td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="font-weight-bold">
                                        <td colspan="4" class="text-right">Total:</td>
                                        <td class="text-right"><?= number_format($fatura->valorTotal, 2) ?>€</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="order-info">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="order-info-item">
                                            <i class="fas fa-credit-card mr-2 text-primary"></i>
                                            <span class="order-info-label">Método de Pagamento:</span>
                                            <?= $fatura->metodopagamento->nome ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="order-info-item">
                                            <i class="fas fa-truck mr-2 text-primary"></i>
                                            <span class="order-info-label">Método de Expedição:</span>
                                            <?= $fatura->metodoexpedicao->nome ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="order-info-item">
                                            <i class="fas fa-info-circle mr-2 text-primary"></i>
                                            <span class="order-info-label">Status da Entrega:</span>
                                            <?= $fatura->statusCompra ?? 'Por Entregar' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="action-buttons text-center">
                            <?= Html::a(
                                '<i class="fas fa-eye mr-2"></i>Ver Detalhes',
                                ['view', 'id' => $fatura->id],
                                ['class' => 'btn btn-view text-white']
                            ) ?>

                            <?php if ($fatura->statusCompra !== 'Entregue'): ?>
                                <?= Html::a(
                                    '<i class="fas fa-check mr-2"></i>Confirmar Entrega',
                                    ['confirm-status', 'id' => $fatura->id],
                                    [
                                        'class' => 'btn btn-confirm text-white',
                                        'data' => [
                                            'confirm' => 'Tem certeza que deseja confirmar a entrega deste pedido?',
                                            'method' => 'post',
                                        ],
                                    ]
                                ) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center p-5">
            <i class="fas fa-info-circle fa-3x mb-3"></i>
            <h4>Ainda não tem faturas</h4>
            <p class="mb-0">Quando você fizer uma compra, suas faturas aparecerão aqui.</p>
        </div>
    <?php endif; ?>
</div>
