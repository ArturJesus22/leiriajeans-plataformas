<?php

namespace backend\modules\api\components;

use Yii;
use yii\filters\auth\AuthMethod;
use common\models\User;

class CustomAuth extends AuthMethod
{
    // Implementa autenticação sem estado (stateless)
    // Cada requisição deve conter todas as informações necessárias
    public function authenticate($user, $request, $response)
    {
        $authToken = $request->getQueryString();
        $token = explode('=', $authToken)[1];
        $user = User::findIdentityByAccessToken($token);
        if (!$user) { throw new \yii\web\ForbiddenHttpException('No authentication'); //403
        } Yii::$app->params['id'] = $user->id; return $user;
    }
}