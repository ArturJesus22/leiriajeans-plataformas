<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Meus Pedidos';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="faturas-index">
    <h1 class="text-center mb-4"><?= Html::encode($this->title) ?></h1>

    <?php if (!empty($dataProvider->models)): ?>
        <?php foreach ($dataProvider->models as $fatura): ?>
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h3>Pedido <?= $fatura->id ?></h3>
                    <div>Data: <?= Yii::$app->formatter->asDate($fatura->data) ?></div>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                        <tr>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Preço</th>
                            <th>IVA</th>
                            <th>Subtotal</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($fatura->linhafaturas as $linhafatura): ?>
                            <tr>
                                <td><?= Html::encode($linhafatura->produto->nome) ?></td>
                                <td><?= $linhafatura->quantidade ?></td>
                                <td><?= Html::encode($linhafatura['precoVenda']) . '€' ?></td>
                                <td><?= Html::encode($linhafatura['valorIva']) . '€' ?></td>
                                <td><?= Html::encode($linhafatura['subTotal']) . '€' ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="4" class="text-right"><strong>Total:</strong></td>
                            <td><strong><?= Html::encode($fatura['valorTotal']) . '€' ?></strong></td>
                        </tr>
                        </tfoot>
                    </table>

                    <div class="mt-3">
                        <strong>Método de Pagamento:</strong> <?= $fatura->metodopagamento->nome ?>
                        <br>
                        <strong>Método de Expedição:</strong> <?= $fatura->metodoexpedicao->nome ?>
                        <br>
                        <strong>Status do Pedido:</strong> <?= $fatura->statuspedido ?>
                    </div>
                </div>
                <div class="card-footer text-center bg-blue">
                    <?= Html::a('Ver Detalhes', ['view', 'id' => $fatura->id], ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info text-center">
            Você ainda não tem faturas.
        </div>
    <?php endif; ?>
</div>
