<?php
namespace backend\modules\api\controllers;

use yii\filters\auth\QueryParamAuth;
use yii\web\Controller;
use yii\rest\ActiveController;
use carbon\carbon;

/**
 * Default controller for the `api` module
 */

class AvaliacoesController extends ActiveController
{
    public $modelClass = 'common\models\Avaliacoes';
    public $produtosModelClass = 'common\models\Produtos';
    public $userModelClass = 'common\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),

        ];
        return $behaviors;
    }
    public function actionIndex()
    {
        return $this->render('index');
    }


    //obter todas as avaliacoes
    public function actionAvaliacoes()
    {
        $avaliacoesModel = new $this->modelClass;
        $registos = $avaliacoesModel::find()->all();
        return [$registos];
    }

    public function actionCriaravaliacao()
    {
        $requestCriarAvaliacao = \Yii::$app->request->post();

        $avaliacoesModel = new $this->modelClass;
        $avaliacao = new $avaliacoesModel;
        $avaliacao->data = Carbon::now();
        $avaliacao->comentario = $requestCriarAvaliacao['comentario'];
        $avaliacao->rating = $requestCriarAvaliacao['rating'];
        $avaliacao->linhafatura_id = $requestCriarAvaliacao['linhafatura_id'];
        $avaliacao->userdata_id = $requestCriarAvaliacao['userdata_id'];
        $avaliacao->save();
        return $avaliacao;

    }


}
