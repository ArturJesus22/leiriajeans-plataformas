<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\MetodosExpedicoes $model */

$this->title = 'Create Metodos Expedicoes';
$this->params['breadcrumbs'][] = ['label' => 'Metodos Expedicoes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="metodos-expedicoes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
