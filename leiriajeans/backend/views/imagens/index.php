<?php

use common\models\Imagem;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var backend\models\ImagemSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Imagem';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="imagens-index">

    <div class="images-gallery">
        <?php foreach ($dataProvider->getModels() as $model): ?>
            <div class="image-item">
                <?php
                // Obtém o caminho correto para a imagem
                $imageUrl = Yii::getAlias('@web/public/imagens/produtos/' . $model->fileName);
                ?>
                <?= Html::a(
                    Html::img($imageUrl, ['alt' => $model->fileName, 'class' => 'img-thumbnail']),
                    ['view', 'id' => $model->id]
                ) ?>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<style>
    .images-gallery {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .image-item {
        width: 150px; /* Ajusta o tamanho conforme necessário */
        text-align: center;
    }
    .img-thumbnail {
        width: 100%;
        height: auto;
        display: block;
        border-radius: 4px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
</style>

