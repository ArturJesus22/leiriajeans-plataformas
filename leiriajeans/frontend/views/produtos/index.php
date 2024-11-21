<?php

use common\Models\Produtos;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var frontend\Models\ProdutosSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Produtos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="produtos-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <div class="row">
        <?php foreach ($dataProvider->getModels() as $model): ?>
            <div class="main">
            <div class="shop_top">
                <div class="container">
                    <div class="row shop_box-top">
                        <div class="col-md-3 shop_box"><a href="single.html">
                                <a href="<?= Yii::$app->urlManager->createUrl(['produtos/view', 'id' => $model->id]) ?>"><img src="../../web/images/calcas.jpg" class="img-responsive" alt=""/>
                                <span class="new-box"></span>
                                <div class="shop_desc">
                                    <h3><a href="#"><?= Html::encode($model->nome) ?></a></h3>
                                    <p><?= Html::encode($model->descricao) ?> </p>
                                    <span class="actual"><?= Html::encode($model->preco) ?></span>
                                    <ul class="buttons">
                                        <li class="cart"><a href="#">Adicionar ao Carrinho</a></li>
                                        <li class="shop_btn">
                                            <a href="<?= Yii::$app->urlManager->createUrl(['produtos/view', 'id' => $model->id]) ?>">Veja mais</a>
                                        </li>
                                        <div class="clear"> </div>
                                    </ul>
                                </div>
                            </a></div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>


    <?php GridView::widget([
      'dataProvider' => $dataProvider,
       'filterModel' => $searchModel,
       'columns' => [
           ['class' => 'yii\grid\SerialColumn'],

          'id',
          'nome',
          'descricao:ntext',
          'preco',
          'sexo',
//            //'tamanho_id',
//            //'cor_id',
//            //'iva_id',
       [
                'class' => ActionColumn::className(),
               'urlCreator' => function ($action, Produtos $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'id' => $model->id]);
                }
           ],
       ],
  ]); ?>




</div>
