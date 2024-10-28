<?php

use common\models\Ivas;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\IvasSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Ivas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ivas-index">


    <p>
        <?= Html::a('Criar Ivas', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            'percentagem',
            'descricao',
            [
                'attribute' => 'status',
                'label' => 'Estado',
                'value' => function ($model) {
                    return $model->status == 1 ? 'Ativo' : 'Desativado';
                },
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Ivas $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
