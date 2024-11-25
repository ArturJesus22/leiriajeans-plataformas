<?php

use yii\helpers\Html;

$this->title = 'Carrinho de Compras';
$this->params['breadcrumbs'][] = $this->title;

// Calcular totais
$totalGeral = 0;
$ivaTotal = 0;
foreach ($cart as $item) {
    $subtotal = $item['preco'] * $item['quantidade'];
    $totalGeral += $subtotal;
    $ivaTotal = $totalGeral * 0.23;
}
?>

<div class="faturas-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!empty($cart)): ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Preço Unitário</th>
                        <th>Quantidade</th>
                        <th>Subtotal</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $item): ?>
                        <tr>
                            <td><?= Html::encode($item['nome']) ?></td>
                            <td><?= Yii::$app->formatter->asCurrency($item['preco']) ?></td>
                            <td><?= $item['quantidade'] ?></td>
                            <td><?= Yii::$app->formatter->asCurrency($item['preco'] * $item['quantidade']) ?></td>
                            <td>
                                <?= Html::a('Remover', ['carrinhos/remove', 'id' => $item['id']], [
                                    'class' => 'btn btn-danger btn-sm',
                                    'data-method' => 'post',
                                    'data-confirm' => 'Tem a certeza que deseja remover este artigo?'
                                ]) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                        <td><strong><?= Yii::$app->formatter->asCurrency($totalGeral) ?></strong></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right"><strong>IVA (23%):</strong></td>
                        <td><strong><?= Yii::$app->formatter->asCurrency($ivaTotal) ?></strong></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Total com IVA:</strong></td>
                        <td><strong><?= Yii::$app->formatter->asCurrency($totalGeral + $ivaTotal) ?></strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

            <div class="text-right mt-3">
                <?= Html::a('Continuar a Comprar', ['produtos/index'], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Finalizar Compra', ['checkout'], ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info">O seu carrinho está vazio. <?= Html::a('Continue a comprar', ['produtos/index']) ?>
        </div>
    <?php endif; ?>
</div>
