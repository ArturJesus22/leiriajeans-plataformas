<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\MetodosPagamentos $model */

$this->title = 'Create Metodos Pagamentos';
$this->params['breadcrumbs'][] = ['label' => 'Metodos Pagamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="metodos-pagamentos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
