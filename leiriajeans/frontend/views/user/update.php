<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var common\models\UsersForm $modelUserData */

$this->title = 'Atualizar Utilizador: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelUserData' => $modelUserData,
    ]) ?>

</div>
