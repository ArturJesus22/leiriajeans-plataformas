<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Cores $model */

$this->title = 'Update Cores: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cores', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cores-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
