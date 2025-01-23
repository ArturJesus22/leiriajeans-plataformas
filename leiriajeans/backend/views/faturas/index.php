<?php

use common\models\Fatura;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\FaturaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Fatura';
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
            //['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'label' => 'Nº Fatura',
            ],
            [
                'attribute' => 'metodopagamento.nome',
                'label' => 'Método de Pagamento',
            ],
            [
                'attribute' => 'metodoexpedicao.nome',
                'label' => 'Método de Expedição',
            ],
            [
                'attribute' => 'data',
                'value' => function ($model) {
                    // Formata a data no formato MM-DD-AAAA
                    return Yii::$app->formatter->asDate($model->data, 'php:d-m-Y');
                },
            ],
            'valorTotal' => [
                'attribute' => 'valorTotal',
                'format' => ['currency', 'EUR'],
            ],
            [
                'attribute' => 'statusCompra',
                'label' => 'Status Encomenda',
            ],
            [
                'class' => ActionColumn::className(),
                'header' => 'Ações',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a(
                            '<i class="fas fa-eye"></i> Ver Fatura',
                            $url,
                            ['title' => 'Visualizar Fatura', 'class' => 'btn btn-sm btn-primary']
                        );
                    },
                ],
            ]
        ],
    ]); ?>

</div>
