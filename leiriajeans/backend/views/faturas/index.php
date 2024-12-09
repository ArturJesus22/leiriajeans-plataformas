<?php

use common\models\Faturas;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\FaturasSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Faturas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faturas-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Criar Fatura', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'metodopagamento_id',
            'metodoexpedicao_id',
            'data',
            'valorTotal',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Faturas $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>

    <h2>Linhas de Fatura</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fatura ID</th>
                <th>Produto</th>
                <th>Preço</th>
                <th>IVA</th>
                <th>Quantidade</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dataProvider->models as $fatura): ?>
                <?php foreach ($fatura->linhafaturas as $linha): ?>
                    <tr>
                        <td><?= Html::encode($linha->id) ?></td>
                        <td><?= Html::encode($linha->fatura_id) ?></td>
                        <td><?= Html::encode($linha->linhacarrinho->produto->nome ?? 'Produto não encontrado') ?></td>
                        <td><?= Yii::$app->formatter->asCurrency($linha->preco) ?></td>
                        <td><?= Yii::$app->formatter->asCurrency($linha->iva->percentagem ?? 0) ?></td>
                        <td><?= Html::encode($linha->linhacarrinho->quantidade ?? 0) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
