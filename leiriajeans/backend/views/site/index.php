<?php

use common\models\UserForm;
use yii\helpers\Url;
use yii\web\JsExpression;
use common\models\User;

$this->title = 'LeiriaJeans - BackOffice';
?>


<div class="col-lg-6">
    <?= \hail812\adminlte\widgets\Alert::widget([
        'type' => 'success',
        'body' => "Bem vindo(a), ". Yii::$app->user->identity->username. "!",
    ]) ?>
</div>

<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <?= \hail812\adminlte\widgets\SmallBox::widget([
            'title' => $numUsersWithAdminRole,
            'text' => 'Administradores Registados',
            'icon' => 'fas fa-user-plus',
            'linkText' => 'Ver Administradores',
            'linkUrl' => Url::toRoute(["/user"]),
        ]) ?>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <?= \hail812\adminlte\widgets\SmallBox::widget([
            'title' => $numUsersWithFuncionarioRole,
            'text' => 'Funcionários Registados',
            'icon' => 'fas fa-user-plus',
            'linkText' => 'Ver Funcionários',
            'linkUrl' => Url::toRoute(["/user"]),
        ]) ?>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <?= \hail812\adminlte\widgets\SmallBox::widget([
            'title' => $numUsersWithClienteRole,
            'text' => 'Clientes Registados',
            'icon' => 'fas fa-user-plus',
            'linkText' => 'Ver Clientes',
            'linkUrl' => Url::toRoute(["/user"]),
        ]) ?>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <?= \hail812\adminlte\widgets\SmallBox::widget([
            'title' => $numProdutos,
            'text' => 'Produto Registados',
            'icon' => 'fas fa-tag',
            'linkText' => 'Ver Produto',
            'linkUrl' => Url::toRoute(["/produtos/index"]),
        ]) ?>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <?= \hail812\adminlte\widgets\SmallBox::widget([
            'title' => $numCores,
            'text' => 'Cor',
            'icon' => 'fas fa-palette',
            'linkText' => 'Ver Cor',
            'linkUrl' => Url::toRoute(["/cores"]),
        ]) ?>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <?= \hail812\adminlte\widgets\SmallBox::widget([
            'title' => '0',
            'text' => 'Produto em Falta',
            'icon' => 'fas fa-box-open',
            'linkText' => 'Consultar Stocks',
            'linkUrl' => Url::toRoute(["/produtos/index"]),
        ]) ?>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <?= \hail812\adminlte\widgets\SmallBox::widget([
            'title' => '0',
            'text' => 'Total de Marcas',
            'icon' => 'fas fa-registered',
            'linkText' => 'Ver Marcas',
        ]) ?>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <?= \hail812\adminlte\widgets\SmallBox::widget([
            'title' => '0',
            'text' => 'Encomendas por enviar',
            'icon' => 'fas fa-boxes',
            'linkText' => 'Ver Encomendas',
            'linkUrl' => Url::toRoute(["/faturas/index"]),
        ]) ?>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <?= \hail812\adminlte\widgets\SmallBox::widget([
            'title' => '0',
            'text' => 'Encomendas por entregar',
            'icon' => 'fas fa-shopping-cart',
            'linkText' => 'Ver Encomendas',
            'linkUrl' => Url::toRoute(["/faturas/index"]),
        ]) ?>
    </div>

</div>