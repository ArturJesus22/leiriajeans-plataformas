<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\Models\Tamanhos $model */

$this->title = 'Create Tamanhos';
$this->params['breadcrumbs'][] = ['label' => 'Tamanhos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tamanhos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
