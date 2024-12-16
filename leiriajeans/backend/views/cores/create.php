<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Cor $model */

$this->title = 'Create Cor';
$this->params['breadcrumbs'][] = ['label' => 'Cor', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cores-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
