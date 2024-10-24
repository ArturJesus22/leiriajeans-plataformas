<?php

use common\models\Carrinhos;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Carrinhos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="carrinhos-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Carrinhos', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'userdata_id',
            'metodopagamento_id',
            'produto_id',
            'ivatotal',
            //'total',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Carrinhos $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
