<?php

use common\models\Pagamentos;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Pagamentos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pagamentos-index">

    <h1><?= Html::encode($this->title) ?></h1>

   <!-- <p>
        <?php /*= Html::a('Create Pagamentos', ['create'], ['class' => 'btn btn-success']) */?>
    </p>-->


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'fatura_id',
                'label' => 'Fatura',
            ],
            [
                'attribute' => 'metodopagamento_id',
                'label' => 'Metodo de Pagamento',
            ],
            'valor',
            'data',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Pagamentos $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
