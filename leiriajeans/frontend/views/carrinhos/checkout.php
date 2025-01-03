<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Fatura $model */
/** @var common\models\LinhaFatura[] $linhasFatura */

$this->title = 'Fatura #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Faturas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fatura-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'metodopagamento_id',
            'metodoexpedicao_id',
            'data',
            'valorTotal',
            'statuspedido',
        ],
    ]) ?>

    <h2>Linhas da Fatura</h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ID</th>
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
                        <td><?= Html::encode($linha->id) ?></td>
                        <td>

                            <?= Html::encode($linha->produto ? $linha->produto->nome : 'Produto não encontrado') ?>
                        </td>
                        <td><?= Yii::$app->formatter->asCurrency($linha->precoVenda) ?></td>
                        <td><?= Html::encode($linha->quantidade) ?></td>
                        <td><?= Yii::$app->formatter->asCurrency($linha->subTotal) ?></td>
                        <td><?= Yii::$app->formatter->asCurrency($linha->valorIva) ?></td>
                    </tr>
                <?php endforeach;
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
