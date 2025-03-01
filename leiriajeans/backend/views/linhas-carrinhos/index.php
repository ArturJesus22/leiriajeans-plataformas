<?php

use common\models\LinhaCarrinho;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Linhas Carrinho';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="linhas-carrinhos-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Linhas Carrinho', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'quantidade',
            'precoVenda',
            'valorIva',
            'subTotal',
            //'carrinho_id',
            //'produto_id',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, LinhaCarrinho $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
