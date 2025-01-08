<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Lista de Cliente';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">


    <p>
    <p>
        <?= Html::a('Criar Cliente <i class="fas fa-plus"></i>', ['create'], ['id' => 'criar-cliente', 'class' => 'btn btn-success']) ?>
    </p>
    </p>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //'id',
            'username',
            'email:email',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    if ($model->status == 9) {
                        return 'Bloqueado';
                    } elseif ($model->status == 10) {
                        return 'Ativado';
                    } else {
                        return 'Desconhecido';
                    }
                },
            ],

            'userform.nome',
            'userform.telefone',
            'userform.rua',
            'userform.codpostal',
            'authAssignment.item_name',
            [
                'class' => ActionColumn::className(),
                'header' => 'Ações', // Título da coluna
                'template' => '{view} {update} {delete}',
                'buttons' =>
                    [
                    'view' => function ($url, $model) {
                        return Html::a(
                            '<i class="fas fa-eye"></i> Ver User', // Ver User
                            $url,
                            ['title' => 'Visualizar User', 'class' => 'btn btn-sm btn-primary']
                        );
                    },
                    'update' => function ($url, $model) {
                        return Html::a(
                            '<i class="fas fa-edit"></i> Editar User', // Editar User
                            $url,
                            ['title' => 'Editar User', 'class' => 'btn btn-sm btn-warning']
                        );
                    },
                    'delete' => function ($url, $model) {
                        // Verifica se o estado do user é "Bloqueado" (9) ou "Ativado" (10)
                        $actionUrl = $model->status == 9
                            ? Url::to(['user/activate', 'id' => $model->id]) // URL para desbloquear
                            : Url::to(['user/delete', 'id' => $model->id]);  // URL para bloquear

                        $buttonText = $model->status == 9 ? 'Desbloquear' : 'Bloquear';
                        $buttonClass = $model->status == 9 ? 'btn-success' : 'btn-danger'; // Cor do botão
                        $icon = $model->status == 9 ? 'fas fa-unlock' : 'fas fa-ban';

                        return Html::a(
                            "<i class=\"$icon\"></i> $buttonText",
                            $actionUrl,
                            [
                                'title' => $model->status == 9 ? 'Desbloquear User' : 'Bloquear User',
                                'class' => "btn btn-sm $buttonClass",
                                'data-confirm' => 'Tem certeza de que deseja alterar o status deste user?',
                                'data-method' => 'post',
                            ]
                        );
                    },
                ],
            ],
        ],
    ]); ?>



</div>
