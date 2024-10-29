<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\UsersForm $model */

$this->title = 'Update Users Form: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users Forms', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="users-form-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
