<?php

use yii\helpers\Html;
use common\models\Fatura;

/** @var yii\web\View $this */
/** @var array $carrinhoAtual */

$this->title = 'Carrinho de Compras';
?>
<div class="carrinho-index">
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
                        <td><?= Html::encode($item['preco']) . '€' ?></td>
                        <td>
                            <?= Html::beginForm(['carrinhos/update-quantidade', 'id' => $item['id']], 'post') ?>
                            <?= Html::input('number', 'quantidade', $item['quantidade'], ['min' => 1, 'class' => 'form-control', 'style' => 'width: 80px; display: inline;']) ?>
                            <?= Html::submitButton('Atualizar', ['class' => 'btn btn-primary btn-sm']) ?>
                            <?= Html::endForm() ?>
                        </td>
                        <td><?= Html::encode($item['subtotal']) . '€' ?></td>
                        <td><?= Html::encode($item['valorIva']) . '€' ?></td>
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
                    <td><strong><?= Html::encode($carrinhoAtual['total']) . '€' ?></strong></td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right"><strong>IVA Total:</strong></td>
                    <td><strong><?= Html::encode($carrinhoAtual['ivatotal']) . '€' ?></strong></td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total c/Iva:</strong></td>
                    <td><strong><?= Html::encode($carrinhoAtual['total'] + $carrinhoAtual['ivatotal'] . '€') ?></strong></td>
                    <td colspan="2"></td>
                </tr>
                </tfoot>
            </table>

            <div class="text-right mt-3">
                <?= Html::a('Continuar a Comprar', ['produtos/index'], ['class' => 'btn btn-primary']) ?>

                <?= Html::a('Criar Fatura', ['faturas/create-from-cart'], ['class' => 'btn btn-success', 'data-method' => 'post', 'data-confirm' => 'Tem certeza que deseja criar uma fatura a partir do carrinho?']) ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            O seu carrinho está vazio. <?= Html::a('Continue a comprar', ['produtos/index']) ?>
        </div>
    <?php endif; ?>
</div>