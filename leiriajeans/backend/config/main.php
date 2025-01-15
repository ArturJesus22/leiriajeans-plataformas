<?php

use yii\log\FileTarget;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'api' => [
            'class' => 'backend\modules\api\ModuleAPI',
        ],
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
            'cookieParams' => ['httponly' => true],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning', 'info'],
                    'logVars' => [],
                    'categories' => ['debug'],
                    'logFile' => '@runtime/logs/app.log',
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                //USERS
                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'api/user',
                    'extraPatterns' => [
                        'GET nomes'=> 'nomes',
                        'GET {username}/dados' => 'dados',
                        'GET {id}' => 'getuserbyid',
                        'POST signup' => 'Signup',

                    ],
                    'tokens' => [
                        '{id}' => '<id:\d+>',
                        '{username}' => '<username:\w+>',
                    ],
                ],
                //produtos
                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'api/produtos',
                    'extraPatterns' => [
                        'GET produtos'=> 'produtos',
                        'GET index'=> 'index',
                    ],
                ],
                //avaliacoes - por acabar
                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'api/avaliacoes',
                    'extraPatterns' => [
                        'GET avaliacoes'=> 'avaliacoes',
                        'POST avaliacoes'=> 'criaravaliacao',
                        'PUT {id}/avaliacoes'=> 'updateavaliacao',
                        'DELETE {id}/avaliacoes'=> 'deleteavaliacao',
                    ],
                ],
                //auth
                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'api/auth',
                    'extraPatterns' => [
                        'POST login' => 'login',
                        'POST signup' => 'signup',
                    ],
                ],
                //falta carrinho/faturas
                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'api/carrinho',
                    'extraPatterns' => [
                        'POST criar' => 'criar',
                        'GET {id}/carrinho'=> 'carrinho',
                        'PUT {id}/carrinho'=> 'updatecarrinho',
                    ],
                ],
                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'api/faturas',
                    'extraPatterns' => [
                        'GET {id}/faturas' => 'faturas',
                        'POST criarfatura'=> 'criar',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                    ],
                ],

                //LINHAS CARRINHOS
                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'api/linhascarrinhos',
                    'extraPatterns' => [
                        'GET dados/<carrinho_id:\d+>' => 'dados',
                        'POST postlinhacarrinho' => 'postlinhacarrinho',
                        'PUT updatelinhacarrinho/<id:\d+>' => 'updatelinhacarrinho',
                        'DELETE deletelinhacarrinho/<id:\d+>' => 'deletelinhacarrinho',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                        '{carrinho_id}' => '<carrinho_id:\\d+>',
                    ],
                ],
            ]
        ],
    ],
    'params' => $params,
];