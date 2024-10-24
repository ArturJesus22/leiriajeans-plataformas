<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Cores $model */

$this->title = 'Create Cores';
$this->params['breadcrumbs'][] = ['label' => 'Cores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cores-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
