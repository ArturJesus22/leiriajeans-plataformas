<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var common\models\UsersForm $modelForm */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<head>
    <link rel="stylesheet" href="<?= Yii::getAlias('@web/css/style.css')?> ">
</head>

<div class="user-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Atualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Apagar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            'email:email',
            [
                'attribute' => 'userform.nome',
                'label' => 'Nome Completo',
            ],
            [
                'attribute' => 'userform.codpostal',
                'label' => 'Código Postal',
            ],
            [
                'attribute' => 'userform.localidade',
                'label' => 'Localidade',
            ],
            [
                'attribute' => 'userform.rua',
                'label' => 'Rua',
            ],
            [
                'attribute' => 'userform.nif',
                'label' => 'NIF',
            ],
            [
                'attribute' => 'userform.telefone',
                'label' => 'Telefone',
            ],
        ],
    ]) ?>
</div>
