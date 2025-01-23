<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Fatura $model */

$this->title = 'Fatura Nº: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Fatura', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="faturas-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
