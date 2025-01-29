

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="<?= Yii::getAlias('@web/public/imagens/logo.png') ?>" alt="Leiriajeans Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">LeiriaJeans</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= Yii::getAlias('@web/public/imagens/user.png') ?>" alt=""/></a>
            </div>
            <div class="info">
                <?php $userid = Yii::$app->user->id; ?>
                <a href="<?= Yii::$app->urlManager->createUrl(['user/perfil']) ?>" class="d-block">    <?= Yii::$app->user->identity->username ?></a>
            </div>
        </div>

        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">

            <?php

            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => [
                    ['label' => 'Dashboard', 'header' => true],
                    ['label' => 'Dashboard', 'icon' => 'tachometer-alt', 'url' => ['/site/index']],


                    ['label' => 'Encomendas', 'header' => true],
                        ['label'=> 'Expedicoes', 'icon'=>'fas fa-clock', 'url'=>['/faturas/pendentes']],
                        ['label'=> 'Metodo Expedicao','icon'=>'fas fa-truck','url'=>['/metodos-expedicoes/index']],
                        ['label'=> 'Metodos de Pagamento','icon'=>'fas fa-credit-card','url'=>['/metodos-pagamentos/index']],
                        ['label'=> 'Faturas','icon'=>'fas fa-file-invoice-dollar','url'=>['/faturas/index']],

                    ['label' => 'Gestão de Dados', 'header' => true],
                        ['label' => 'Administrar contas', 'icon' => 'user', 'url' => ['user/index'], 'options' => ['id' => 'admin']],
                        ['label' => 'IVAS', 'icon' => 'fa-solid fa-percent', 'url' => ['ivas/index'], 'visible'],
                        ['label' => 'Empresa', 'icon' => 'fa-solid fa-building', 'url' => ['empresa/index'], 'visible' => ($userRole = Yii::$app->user->can('admin') )],
                        ['label' => 'Avaliações', 'icon' => 'fa-solid fa-star', 'url' => ['avaliacoes/index']],


                    ['label' => 'Produto', 'header' => true],
                        ['label' => 'Categoria', 'icon' => 'fa-regular fa-folder', 'url' => ['categorias/index'], 'options' => ['id' => 'categoria']],
                        ['label' => 'Produto', 'icon' => 'fa-solid fa-box', 'url' => ['produtos/index']],
                        ['label' => 'Tamanho', 'icon' => 'fa-regular fa-ruler combined', 'url' => ['tamanhos/index']],
                        ['label' => 'Cor', 'icon' => 'fa-solid fa-tag', 'url' => ['cores/index']],
                        ['label'=>'Imagem','icon'=>'fa-regular fa-image', 'url'=>['imagens/index']],


                    /*['label' => 'Debug Tools', 'header' => true, 'visible' => ($userRole == 'admin')],
                    ['label' => 'Gii', 'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank', 'visible' => ($userRole == 'admin')],
                    ['label' => 'Debug', 'icon' => 'bug', 'url' => ['/debug'], 'target' => '_blank', 'visible' => ($userRole == 'admin')],*/
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>