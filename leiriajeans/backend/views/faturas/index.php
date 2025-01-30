<?php
use common\models\Fatura;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\FaturaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Faturas';
$this->params['breadcrumbs'][] = $this->title;
?>

<link href="<?= Yii::getAlias('@web/css/faturas.css') ?>?v=<?= time() ?>" rel="stylesheet">

<div class="faturas-index">
    <!-- Status Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Em Processamento</h5>
                    <h3 class="card-text"><?= Fatura::find()->where(['statusCompra' => 'Em Processamento'])->count() ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Enviadas</h5>
                    <h3 class="card-text"><?= Fatura::find()->where(['statusCompra' => 'Enviado'])->count() ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Entregues</h5>
                    <h3 class="card-text"><?= Fatura::find()->where(['statusCompra' => 'Entregue'])->count() ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs for different status -->
    <ul class="nav nav-tabs mb-3" id="orderTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="processing-tab" data-toggle="tab" href="#processing" role="tab">Em Processamento</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="sent-tab" data-toggle="tab" href="#sent" role="tab">Enviadas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="delivered-tab" data-toggle="tab" href="#delivered" role="tab">Entregues</a>
        </li>
    </ul>

    <div class="tab-content" id="orderTabsContent">
        <?php
        $statuses = ['Em Processamento', 'Enviado', 'Entregue'];
        $tabIds = ['processing', 'sent', 'delivered'];

        foreach ($statuses as $index => $status) {
            $isActive = $index === 0 ? 'show active' : '';
            $statusDataProvider = new \yii\data\ActiveDataProvider([
                'query' => Fatura::find()->where(['statusCompra' => $status]),
            ]);
            ?>
            <div class="tab-pane fade <?= $isActive ?>" id="<?= $tabIds[$index] ?>" role="tabpanel">
                <?= GridView::widget([
                    'dataProvider' => $statusDataProvider,
                    'columns' => [
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
                                return Yii::$app->formatter->asDate($model->data, 'php:d-m-Y');
                            },
                        ],
                        [
                            'attribute' => 'valorTotal',
                            'format' => ['currency', 'EUR'],
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
        <?php } ?>
    </div>
</div>



