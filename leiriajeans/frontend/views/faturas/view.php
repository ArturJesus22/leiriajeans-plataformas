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
<div class="fatura-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Exibir métodos de pagamento e expedição -->
    <h3>Método de Pagamento</h3>
    <p><?= Html::encode($metodoPagamento ? $metodoPagamento->nome : 'Método de pagamento não encontrado') ?></p> <!-- Exibe o nome do método de pagamento -->

    <h3>Método de Expedição</h3>
    <p><?= Html::encode($metodoExpedicao ? $metodoExpedicao->nome : 'Método de expedição não encontrado') ?></p> <!-- Exibe o nome do método de expedição -->

<!--    <p>-->
<!--        --><?php //= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
<!--        --><?php //= Html::a('Delete', ['delete', 'id' => $model->id], [
//            'class' => 'btn btn-danger',
//            'data' => [
//                'confirm' => 'Are you sure you want to delete this item?',
//                'method' => 'post',
//            ],
//        ]) ?>
<!--    </p>-->
<!---->
<!--    --><?php //= DetailView::widget([
//        'model' => $model,
//        'attributes' => [
//            'id',
//            'metodopagamento_id',
//            'metodoexpedicao_id',
//            'data',
//            'valorTotal',
//            'statuspedido',
//        ],
//    ]) ?>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
<!--                <th>ID</th>-->
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
                echo '<tr><td colspan="6">Nenhuma linha de fatura encontrada.</td></tr>';
            } else {
                foreach ($linhasFatura as $linha): ?>
                    <tr>
<!--                        <td>--><?php //= Html::encode($linha->id) ?><!--</td>-->
                        <td>

                            <?= Html::encode($linha->produto ? $linha->produto->nome : 'Produto não encontrado') ?>
                        </td>
                        <td><?= Html::encode($linha['precoVenda']) . '€' ?></td>
                        <td><?= Html::encode($linha->quantidade) ?></td>
                        <td><?= Html::encode($linha['subTotal']) . '€' ?></td>
                        <td><?= Html::encode($linha['valorIva']) . '€' ?></td>
                        <!--metodo pagamento-->
                        <!--metodo expedicao-->
                    </tr>
                <?php endforeach;
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
