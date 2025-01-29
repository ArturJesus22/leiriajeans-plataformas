<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Faturas Pendentes';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="fatura-pendentes">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-clock mr-2"></i>
                <?= Html::encode($this->title) ?>
            </h3>
        </div>
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
                    [
                        'attribute' => 'data',
                        'format' => ['date', 'php:d/m/Y']
                    ],
                    [
                        'attribute' => 'statusCompra',
                        'format' => 'raw',
                        'value' => function($model) {
                            $badgeClass = $model->statusCompra === 'Em Processamento' ? 'warning' : 'info';
                            return Html::tag('span',
                                $model->statusCompra,
                                ['class' => "badge badge-$badgeClass"]
                            );
                        }
                    ],
                    [
                        'attribute' => 'valorTotal',
                        'value' => function($model) {
                            return number_format($model->total, 2) . '€';
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update}',
                        'buttons' => [
                            'view' => function($url, $model) {
                                return Html::a(
                                    '<i class="fas fa-eye"></i>',
                                    ['view', 'id' => $model->id],
                                    ['class' => 'btn btn-sm btn-info']
                                );
                            },
                            'update' => function($url, $model) {
                                return Html::a(
                                    '<i class="fas fa-edit"></i>',
                                    ['update', 'id' => $model->id],
                                    ['class' => 'btn btn-sm btn-primary ml-1']
                                );
                            }
                        ]
                    ],
                ],
                'options' => ['class' => 'table-responsive'],
                'tableOptions' => ['class' => 'table table-striped table-bordered'],
                'summary' => false,
                'pager' => [
                    'options' => ['class' => 'pagination justify-content-center'],
                    'linkContainerOptions' => ['class' => 'page-item'],
                    'linkOptions' => ['class' => 'page-link'],
                ]
            ]); ?>
        </div>
    </div>
</div>
