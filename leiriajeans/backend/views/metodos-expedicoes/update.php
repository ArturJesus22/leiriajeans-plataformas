<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\MetodoExpedicao $model */

$this->title = 'Update Metodos Expedicoes: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Metodos Expedicoes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="metodos-expedicoes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
