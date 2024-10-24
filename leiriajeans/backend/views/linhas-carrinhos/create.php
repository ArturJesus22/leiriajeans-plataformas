<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\LinhasCarrinhos $model */

$this->title = 'Create Linhas Carrinhos';
$this->params['breadcrumbs'][] = ['label' => 'Linhas Carrinhos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="linhas-carrinhos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
