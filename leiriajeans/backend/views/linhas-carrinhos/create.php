<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\LinhaCarrinho $model */

$this->title = 'Create Linhas Carrinho';
$this->params['breadcrumbs'][] = ['label' => 'Linhas Carrinho', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="linhas-carrinhos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
