<?php

use yii\helpers\Html;

$this->title = 'Carrinho de Compras';
$this->params['breadcrumbs'][] = $this->title;

$carrinhoAtual = Yii::$app->controller->getCarrinhoAtual();
?>

<div class="faturas-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($carrinhoAtual && !empty($carrinhoAtual['itens'])): ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Preço Unitário</th>
                        <th>Quantidade</th>
                        <th>Subtotal</th>
                        <th>IVA</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carrinhoAtual['itens'] as $item): ?>
                        <tr>
                            <td><?= Html::encode($item['nome']) ?></td>
                            <td><?= Yii::$app->formatter->asCurrency($item['preco']) ?></td>
                            <td><?= $item['quantidade'] ?></td>
                            <td><?= Yii::$app->formatter->asCurrency($item['subtotal']) ?></td>
                            <td><?= Yii::$app->formatter->asCurrency($item['valorIva']) ?></td>
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
                        <td><strong><?= Yii::$app->formatter->asCurrency($carrinhoAtual['total']) ?></strong></td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right"><strong>IVA Total:</strong></td>
                        <td><strong><?= Yii::$app->formatter->asCurrency($carrinhoAtual['ivatotal']) ?></strong></td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Total com IVA:</strong></td>
                        <td><strong><?= Yii::$app->formatter->asCurrency($carrinhoAtual['total'] + $carrinhoAtual['ivatotal']) ?></strong></td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>

            <div class="text-right mt-3">
                <?= Html::a('Continuar a Comprar', ['produtos/index'], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Finalizar Compra', ['checkout'], ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            O seu carrinho está vazio. <?= Html::a('Continue a comprar', ['produtos/index']) ?>
        </div>
    <?php endif; ?>
</div>
