<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\LinhaCarrinho $model */

$this->title = 'Update Linhas Carrinho: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Linhas Carrinho', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="linhas-carrinhos-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
