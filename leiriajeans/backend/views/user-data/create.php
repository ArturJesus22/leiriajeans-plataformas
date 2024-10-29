<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\UsersForm $model */

$this->title = 'Create Users Form';
$this->params['breadcrumbs'][] = ['label' => 'Users Forms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-form-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
