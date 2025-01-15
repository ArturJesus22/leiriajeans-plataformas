<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Fatura $model */
/** @var common\models\LinhaFatura[] $linhasFatura */

$this->title = 'Número do pedido: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Faturas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fatura-view container mt-4">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">
                    <strong>
                        Método de Pagamento
                    </strong>
                </div>
                <div class="card-body">
                    <p class="text-muted"><?= Html::encode($metodoPagamento ? $metodoPagamento->nome : 'Método de pagamento não encontrado') ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">
                    <strong>
                        Método de Expedição
                    </strong>
                </div>
                <div class="card-body">
                    <p class="text-muted"><?= Html::encode($metodoExpedicao ? $metodoExpedicao->nome : 'Método de expedição não encontrado') ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header">
                    <strong>
                        Estado do Pedido
                    </strong>
                </div>
                <div class="card-body">
                    <p class="text-muted"><?= Html::encode($model ? $model->statuspedido : 'Status pedido ' . $model->statuspedido = 'anulada') ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <strong>
                Detalhes da Fatura
            </strong>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
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
                    if (empty($linhasFatura)) {
                        echo '<tr><td colspan="5" class="text-center">Nenhuma linha de fatura encontrada.</td></tr>';
                    } else {
                        foreach ($linhasFatura as $linha): ?>
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
        </div>
    </div>
</div>
