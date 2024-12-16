<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Cor $model */

$this->title = 'Update Cor: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cor', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cores-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
