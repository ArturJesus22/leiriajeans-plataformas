<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Minhas Fatura';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="faturas-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!empty($dataProvider->models)): ?>
        <?php foreach ($dataProvider->models as $fatura): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Fatura #<?= $fatura->id ?></h3>
                    <div>Data: <?= Yii::$app->formatter->asDate($fatura->data) ?></div>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
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
                                    <td><?= Yii::$app->formatter->asCurrency($linhafatura->precoVenda) ?></td>
                                    <td><?= Yii::$app->formatter->asCurrency($linhafatura->valorIva) ?></td>
                                    <td><?= Yii::$app->formatter->asCurrency($linhafatura->subTotal) ?></td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right"><strong>Total:</strong></td>
                                <td><strong><?= Yii::$app->formatter->asCurrency($fatura->valorTotal) ?></strong></td>
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
                <div class="card-footer">
                    <?= Html::a('Ver Detalhes', ['view', 'id' => $fatura->id], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info">
            Você ainda não tem faturas.
        </div>
    <?php endif; ?>
</div>
