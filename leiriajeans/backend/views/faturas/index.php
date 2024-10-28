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




    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            [
                'attribute' => 'pagamento_id',
                'label' => 'Pagamento',
            ],
            [
                'attribute' => 'metodoexpedicao_id',
                'label' => 'Metodo Expedicao',
            ],
            [
                'attribute' => 'data',
                'label' => 'Data',
            ],
            [
                'attribute' => 'valorTotal',
                'label' => 'Valor Total',
            ],
            [
                'attribute' => 'statuspedido',
                'label' => 'Estado de Pagamento',
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Faturas $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
