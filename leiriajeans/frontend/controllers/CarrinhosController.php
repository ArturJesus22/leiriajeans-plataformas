<?php

namespace frontend\controllers;

use Yii;
use yii\web\Response;
use common\models\Produtos;
use common\models\Carrinhos;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\models\UsersForm;

/**
 * CarrinhosController implements the CRUD actions for Carrinhos model.
 */
class CarrinhosController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'add' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if ($action->id === 'add') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionAdd()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        try {
            if (Yii::$app->user->isGuest) {
                return ['success' => false, 'message' => 'Faça login primeiro.'];
            }

            $userForm = UsersForm::findOne(['user_id' => Yii::$app->user->id]);
            if (!$userForm) {
                return ['success' => false, 'message' => 'Utilizador não encontrado.'];
            }

            $id = Yii::$app->request->post('id');
            $produto = Produtos::findOne($id);

            if (!$produto) {
                return ['success' => false, 'message' => 'Produto não encontrado.'];
            }

            // Guardar na sessao
            $session = Yii::$app->session;
            $cart = $session->get('cart', []);

            if (isset($cart[$id])) {
                $cart[$id]['quantidade']++;
            } else {
                $cart[$id] = [
                    'id' => $id,
                    'nome' => $produto->nome,
                    'preco' => $produto->preco,
                    'quantidade' => 1,
                ];
            }

            $session->set('cart', $cart);

            // Guardar na base de dados
            $carrinho = new Carrinhos([
                'userdata_id' => $userForm->id,
                'produto_id' => $id,
                'total' => $produto->preco,
                'ivatotal' => $produto->preco * $produto->iva_id,
            ]);

            if (!$carrinho->save()) {
                return ['success' => false, 'message' => 'Erro ao guardar no carrinho: '];
            }

            return ['success' => true, 'message' => 'Produto adicionado ao carrinho com sucesso!'];

        } catch (\Exception $e) {
            Yii::error('Erro ao adicionar ao carrinho: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Erro ao adicionar produto: ' . $e->getMessage()];
        }
    }

    public function actionRemove($id)
    {
        // Remover da sessão
        $session = Yii::$app->session;
        $cart = $session->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            $session->set('cart', $cart);

            // Remover da base de dados
            if (!Yii::$app->user->isGuest) {
                $userForm = UsersForm::findOne(['user_id' => Yii::$app->user->id]);
                if ($userForm) {
                    Carrinhos::deleteAll([
                        'userdata_id' => $userForm->id,
                        'produto_id' => $id
                    ]);
                }
            }
        }

        return $this->redirect(['faturas/index']);
    }

    public function carregarCarrinho()
    {
        if (!Yii::$app->user->isGuest) {
            $userForm = UsersForm::findOne(['user_id' => Yii::$app->user->id]);
            if ($userForm) {
                $carrinhoItems = Carrinhos::find()
                    ->where(['userdata_id' => $userForm->id])
                    ->all();

                $session = Yii::$app->session;
                $cart = [];

                foreach ($carrinhoItems as $item) {
                    $produto = Produtos::findOne($item->produto_id);
                    if ($produto) {
                        $cart[$produto->id] = [
                            'id' => $produto->id,
                            'nome' => $produto->nome,
                            'preco' => $produto->preco,
                            'quantidade' => 1,
                        ];
                    }
                }

                $session->set('cart', $cart);
            }
        }
    }
}
