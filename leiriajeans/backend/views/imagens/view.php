<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Imagens $model */

$this->title = "Nome do Produto: " . $model->produto->nome;
$this->params['breadcrumbs'][] = ['label' => 'Imagens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>

<div class="imagens-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="image-details">
        <!-- Exibir a imagem -->
        <?php
        $imageUrl = Yii::getAlias('@web/public/imagens/produtos/' . $model->fileName);
        ?>
        <div class="image">
            <?= Html::img($imageUrl, ['alt' => $model->fileName, 'class' => 'img-thumbnail']) ?>
        </div>
    </div>

</div>

<style>
    .image-details {
        margin-top: 20px;
    }

    .image {
        text-align: center;
        margin-bottom: 20px;
    }

    .product-info {
        font-size: 16px;
    }

    .img-thumbnail {
        max-width: 800px;
        height: auto;
        display: block;
        margin: 0 auto;
    }
</style>

