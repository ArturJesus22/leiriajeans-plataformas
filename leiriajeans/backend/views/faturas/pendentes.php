<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Encomendas Pendentes';
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
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'Nº da Fatura',
                        'value' => 'id'
                    ],
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
                            return number_format($model->valorTotal, 2) . '€';
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {expedir}',
                        'buttons' => [
                            'view' => function($url, $model) {
                                return Html::a(
                                    '<i class="fas fa-eye"></i>',
                                    ['view', 'id' => $model->id],
                                    ['class' => 'btn btn-sm btn-info']
                                );
                            },
                            'expedir' => function($url, $model) {
                                if ($model->statusCompra === 'Em Processamento') {
                                    return Html::a(
                                        'Expedir',
                                        ['enviar', 'id' => $model->id],
                                        [
                                            'class' => 'btn btn-sm btn-success ml-1',
                                            'data' => [
                                                'confirm' => 'Tem certeza que deseja expedir esta fatura?',
                                                'method' => 'post',
                                            ],
                                        ]
                                    );
                                }
                                return '';
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
