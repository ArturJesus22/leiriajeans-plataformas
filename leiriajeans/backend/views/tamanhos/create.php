<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Tamanho $model */

$this->title = 'Create Tamanho';
$this->params['breadcrumbs'][] = ['label' => 'Tamanho', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tamanhos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
