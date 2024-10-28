<?php

use common\models\MetodosPagamentos;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Metodos Pagamentos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="metodos-pagamentos-index">


    <p>
        <?= Html::a('Criar Metodos Pagamentos', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            'nome',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, MetodosPagamentos $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
