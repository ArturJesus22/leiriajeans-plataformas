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
            'header' => 'Ações', // Cabeçalho da coluna
            'template' => '{view} {update} {delete}', // Define os botões que serão exibidos
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a(
                        '<i class="fas fa-eye"></i> Ver',
                        $url,
                        ['title' => 'Ver', 'class' => 'btn btn-sm btn-primary']
                    );
                },
                'update' => function ($url, $model) {
                    return Html::a(
                        '<i class="fas fa-edit"></i>Editar',
                        $url,
                        ['title' => 'Editar', 'class' => 'btn btn-sm btn-warning']
                    );
                },
                'delete' => function ($url, $model) {
                    $blockUrl = Url::to(['metodospagamentos/delete', 'id' => $model->id]); // URL para a ação "block"
                    return Html::a(
                        '<i class="fas fa-trash"></i> Apagar',
                        $blockUrl,
                        [
                            'title' => 'Apagar',
                            'class' => 'btn btn-sm btn-danger',
                            'data-confirm' => 'Tem certeza de que deseja apagar este Método Expedição?', // Confirmação
                            'data-method' => 'post', // Método POST para segurança
                        ]
                     );
                    },
                  ],
                ],
        ],
    ]); ?>


</div>
