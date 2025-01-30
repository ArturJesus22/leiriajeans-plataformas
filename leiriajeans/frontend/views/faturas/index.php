<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Os meus Pedidos';
$this->params['breadcrumbs'][] = $this->title;
?>

<link href="<?= Yii::getAlias('@web/css/style.css') ?>?v=<?= time() ?>" rel="stylesheet">

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

                            <?php if ($fatura->statusCompra === 'Enviado'): ?>
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
