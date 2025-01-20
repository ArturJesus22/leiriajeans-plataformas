<?php

namespace backend\modules\api\controllers;

use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

class MetodosExpedicoesController extends ActiveController
{
    public $modelClass = 'common\models\MetodoExpedicao';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::class,
        ];
        return $behaviors;
    }

    /**
     * Devolve os métodos de expedição disponíveis
     * @return array
     */
    public function actionIndex()
    {
        $metodos = $this->modelClass::find()->where(['ativo' => 1])->all();

        if (empty($metodos)) {
            throw new \yii\web\NotFoundHttpException('Nenhum método de expedição disponível.');
        }

        $result = [];
        foreach ($metodos as $metodo) {
            $result[] = [
                'id' => $metodo->id,
                'nome' => $metodo->nome,
                'descricao' => $metodo->descricao,
                'custo' => $metodo->custo,
                'prazo_entrega' => $metodo->prazo_entrega,
            ];
        }

        return $result;
    }
}
