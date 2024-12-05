<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\Models\LinhasCarrinhos $model */

$this->title = 'Update Linhas Carrinhos: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Linhas Carrinhos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="linhas-carrinhos-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
