<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Atualizar Dados', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <br>

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

    <p>
        <?= Html::a('Logout', ['/site/logout'], [
            'class' => 'btn btn-danger',
            'data-method' => 'post',
            'data-confirm' => 'Tem a certeza de que deseja terminar sessão?',
        ]) ?>
    </p>

    <br>
    <br>
    <br>

</div>
