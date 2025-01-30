<?php
namespace backend\modules\api\controllers;

use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\web\Controller;
use yii\rest\ActiveController;
use Carbon\Carbon;

/**
 * Default controller for the `api` module
 */

class AvaliacoesController extends ActiveController
{
    public $modelClass = 'common\models\Avaliacao';
    public $produtosModelClass = 'common\models\Produto';
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
        $comentario = Yii::$app->request->post('comentario');
        $rating = Yii::$app->request->post('rating');
        $linhafatura = Yii::$app->request->post('linhafatura_id');
        $userdata = Yii::$app->request->post('userdata_id');


        $avaliacoesModel = new $this->modelClass;
        $avaliacao = new $avaliacoesModel;
        $avaliacao->data = Carbon::now();
        $avaliacao->comentario = $comentario;
        $avaliacao->rating = $rating;
        $avaliacao->linhafatura_id = $linhafatura;
        $avaliacao->userdata_id = $userdata;
        $avaliacao->save();
        return $avaliacao;

    }

    public function actionUpdateavaliacao($id)
    {
        $requestPostAvaliacao = \Yii::$app->request->post();

        $avaliacoesModel = new $this->modelClass;
        $avaliacao = $avaliacoesModel::find()->where(['id' => $id])->one();
        if ($avaliacao) {
            $avaliacao->comentario = $requestPostAvaliacao['comentario'];
            $avaliacao->rating = $requestPostAvaliacao['rating'];
            $avaliacao->data = Carbon::now();

            $avaliacao->save();
        } else {
            throw new \yii\web\NotFoundHttpException("Avaliação não existe");
        }
        return [$avaliacao];

    }

    public function actionDeleteavaliacao($id)
    {
        $avaliacoesModel = new $this->modelClass;
        $avaliacao = $avaliacoesModel::find()->where(['id' => $id])->one();
        if ($avaliacao == null) {
            throw new \yii\web\NotFoundHttpException("Avaliação não existe");
        } else {
            $avaliacao->delete();
            return [
                'avaliacao' => $avaliacao,
                'mensagem' => 'Avaliação eliminada com sucesso'
            ];

        }
    }

}
