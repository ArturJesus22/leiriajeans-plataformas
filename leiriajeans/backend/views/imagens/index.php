<?php

use common\models\Imagens;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var backend\models\ImagensSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Imagens';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="imagens-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Imagens', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="images-gallery">
        <?php foreach ($dataProvider->getModels() as $model): ?>
            <div class="image-item">
                <?php
                $imageUrl = Yii::getAlias('@web/backend/web/public/images/produtos/' . $model->fileName);
                ?>
                <?= Html::a(
                    Html::img($imageUrl, ['alt' => $model->fileName, 'class' => 'img-thumbnail']),
                    ['view', 'id' => $model->id]
                ) ?>
            </div>
        <?php endforeach; ?>
    </div>


    <style>
        .images-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .image-item {
            width: 150px; /* Ajuste o tamanho conforme necessário */
        }
        .img-thumbnail {
            width: 100%;
            height: auto;
            display: block;
        }
    </style>

</div>


