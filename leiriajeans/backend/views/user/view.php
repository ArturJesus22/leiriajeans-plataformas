<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\widgets\Alert;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var common\models\UserForm $modelForm */

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
    </p>

    <?php
    //Mensagem de flash de erro
    if (Yii::$app->session->hasFlash('error')) {
        echo '<div class="alert alert-danger">' . Yii::$app->session->getFlash('error') . '</div>';
    }
    ?>


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
            //role

        ],
    ]) ?>
</div>
