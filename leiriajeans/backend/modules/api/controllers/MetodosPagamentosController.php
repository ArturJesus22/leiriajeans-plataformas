<?php

namespace backend\modules\api\controllers;

use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

class MetodosPagamentosController extends ActiveController
{
    public $modelClass = 'common\models\MetodoPagamento';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::class,
        ];
        return $behaviors;
    }

    /**
     * Devolve os métodos de pagamento disponíveis
     * @return array
     */
    public function actionIndex()
    {
        $metodos = $this->modelClass::find()->all();

        if (empty($metodos)) {
            throw new \yii\web\NotFoundHttpException('Nenhum método de pagamento disponível.');
        }

        $result = [];
        foreach ($metodos as $metodo) {
            $result[] = [
                'id' => $metodo->id,
                'nome' => $metodo->nome,
            ];
        }

        return $result;
    }
}
