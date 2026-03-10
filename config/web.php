<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'sourceLanguage' => 'ru-RU',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'timeZone' => 'Europe/Moscow',
    'components' => [
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'php:d.m.Y',
            'datetimeFormat' => 'php:j F, H:i',
            'timeFormat' => 'php:H:i:s',
            'defaultTimeZone' => 'Europe/Moscow',
            'locale' => 'ru-RU'
        ],
         'jwt' => [
            'class' => 'app\components\JwtService',
            'secret' => 'SUPER_SECRET_KEY_1512840101', // замените на свой секретный ключ
            'accessTokenExpire' => 3600, // 1 час
            'refreshTokenExpire' => 2592000, // 30 дней
        ],
        'session'=>[
            'class' => 'yii\web\Session',
            'cookieParams' => ['lifetime' => 7 * 24 * 60 * 60]
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'request' => [
            'cookieValidationKey' => '5ubeQxfWUKXsufmpopdXrYm97_8aeBFf1',
            'baseUrl' => '',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['site/login'],
        ],      
        'storage' => [
            'class' => 'app\components\Storage',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'htmlLayout' => 'layouts/html',
            'textLayout' => 'layouts/text',
            'messageConfig' => [
                'charset' => 'UTF-8',
            ],
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.com',
                'username' => 'v@igoevent.com',
                'password' => 'ncjlxyrdxwshxuqr',
                'port' => '465',
                'encryption' => 'ssl',
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'view' => [
            'class' => 'yii\web\View',
            'renderers' => [
                'twig' => [
                    'class' => 'yii\twig\ViewRenderer',
                    'cachePath' => '@runtime/Twig/cache',
                    // Array of twig options:
                    'options' => [
                        'auto_reload' => true,
                    ],
                    'globals' => ['html' => '\yii\helpers\Html'],
                    'uses' => ['yii\bootstrap'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            // 'suffix' => '/',
            'rules' => [
                'post/<controller>' => 'post/<controller>/index',
                'post/<controller>/<action>' => 'post/<controller>/<action>',
                'service/<action>' => 'service/<action>',
                'yoo/<action>' => 'yoo/<action>',
                'money/<action>' => 'money/<action>',
                'ajax/<action>' => 'ajax/<action>',
                'api/<action>' => 'api/<action>',
                'devhelper/<action>' => 'devhelper/<action>',
                'form/<action>' => 'form/<action>',
                'page/<alias>' => 'site/page',
                'newsmakertask/<alias>' => 'site/newsmakertask',
                'pay/<action>' => 'pay/<action>',
                'band/<alias>' => 'site/band',
                'band/<alias>/festivals' => 'site/band',
                'todo/<alias>' => 'site/todo',
                'crm/company' => 'crm/company/about',
                'crm' => '/crm/company/my',
                'crm/' => '/crm/company/my',
                'crm/choose' => '/crm/company/my',
                'crm/<controller>' => 'crm/<controller>/index',
                'crm/<controller>/<action>' => 'crm/<controller>/<action>',
                'choose' => '/crm/company/my',
                'date' => 'crm/events',
                'doc' => 'site/doc',
                'events' => 'crm/biblioevents',
                'inviting' => 'crm/inviting/create',
                'partners' => 'crm/partners/index',
                'clients' => 'crm/clients/index',
                'placers' => 'crm/placers/index',
                'admin' => '/admin/default/index',
                'admin/<controller>' => 'admin/<controller>/index',
                'admin/<controller>/<action>' => 'admin/<controller>/<action>',
                'kudago/<controller>' => 'kudago/<controller>/index',
                'kudago/<controller>/<action>' => 'kudago/<controller>/<action>',
                'rbac/<controller>' => 'rbac/<controller>/index',
                'rbac/<controller>/<action>' => 'rbac/<controller>/<action>',
                'profile' => 'profile/index',
                'tester' => 'tester/index',
                'profile/<action>' => 'profile/<action>',
                'goto' => 'goto/index',
                'goto/<action>' => 'goto/<action>',
                'site/<action>' => 'site/<action>',
                'test/<action>' => 'test/<action>',
                'book/<action>' => 'book/<action>',
                'site' => 'site/index',
                'bands' => 'site/bands',
                'places' => 'site/siteplaces',
                'rider/<alias>' => 'site/rider',
                'site/<action>/<id>' => 'site/<action>/',
                'shop/<action>' => 'shop/<action>',
                'vk/<action>' => 'vk/<action>',
                'messages/<action>' => 'messages/<action>',
                'login' => 'site/login',
                'signup' => 'site/signup',
                'quote/<action>' => 'quote/<action>',
                '<city>' => 'site/city',
                '<city>/places' => 'site/places',
                '<city>/list/places' => 'site/placeslist',
                '<city>/promo' => 'site/promo',
                '<city>/events' => 'site/allowedIPsevents',
                '<city>/<section>' => 'site/section',
                '<city>/event/<alias>' => 'site/landing',
                '<city>/book/<alias>' => 'book/landing',
                '<city>/place/<alias>' => 'site/place',
                '<controller>/<action>' => '<controller>/<action>',
                '<module>/<controller>/<action>' => '<module>/<controller>/<action>',
                'lll' => 'site/allevents',
                'manager' => 'site/manager',

            ],
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'vkontakte' => [
                    'class'        => 'yii\authclient\clients\VKontakte',
                    'clientId'     => '7588147',
                    'clientSecret' => 'MbQJc4Q3IN9gNRJvgQWJ',
                ],
                // 'facebook' => [
                //     'class' => 'yii\authclient\clients\Facebook',
                //     'clientId' => '685005952392675',
                //     'clientSecret' => '3a908b2fb85071642b981748bf24a669',
                // ],
                // 'google' => [
                //     'class' => 'yii\authclient\clients\Google',
                //     'clientId' => '22',
                //     'clientSecret' => '22',
                // ],
            ],
        ]
    ],
    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
            // enter optional module parameters below - only if you need to  
            // use your own export download action or custom translation 
            // message source
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
        ],
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
        'crm' => [
            'class' => 'app\modules\crm\Module',
        ],
        'post' => [
            'class' => 'app\modules\post\Module',
        ],
        'kudago' => [
            'class' => 'app\modules\kudago\Module',
        ],
        'rbac' => [
            'class' => 'mdm\admin\Module',
            'controllerMap' => [
                'assignment' => [
                    'class' => 'mdm\admin\controllers\AssignmentController',
                    /* 'userClassName' => 'app\models\User', */
                    'idField' => 'id',
                    'usernameField' => 'username',
                ],
            ],
            'layout' => 'left-menu',
            'mainLayout' => '@app/views/layouts/admin.php',
        ]
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'ajax/*',
            'api/*',
            'yoo/*',
            'vk/*',
            'form/*',
            'site/*',
            'test/*',
            'book/*',
            'messages/*',
            'money/*',
            'quote/*',
            'pay/*',
            'service/*',
            'shop/*',
            'avisourl/*',
            'checkurl/*',
            'smena/smenaavtoclose',
            'abonements/daycheck',
            'debug/*',
            'gii/*'
        ]
    ],
    'params' => $params,
];


if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '188.170.194.134', '91.205.147.154', '182.253.132.144', '36.75.126.240', '36.85.191.142', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '91.205.144.249', '::1'],
    ];
}

return $config;
