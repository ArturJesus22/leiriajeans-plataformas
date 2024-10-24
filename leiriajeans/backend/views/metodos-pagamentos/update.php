<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\MetodosPagamentos $model */

$this->title = 'Update Metodos Pagamentos: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Metodos Pagamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="metodos-pagamentos-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
