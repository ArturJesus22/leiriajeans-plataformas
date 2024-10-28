<?php

use common\models\MetodosExpedicoes;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Metodos Expedicoes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="metodos-expedicoes-index">

    <p>
        <?= Html::a('Criar Metodos Expedicoes', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            'nome',
            'descricao:ntext',
            'custo',
            'prazo_entrega',
            [
                'attribute' => 'ativo',
                'label' => 'Estado',
                'value' => function ($model) {
                    return $model->ativo == 1 ? 'Ativo' : 'Desativado';
                },
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, MetodosExpedicoes $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
