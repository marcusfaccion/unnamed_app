<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'aplicacao-ACC',
    'name' => 'Apicação Colaborativa para Ciclistas',	
    'basePath' => dirname(__DIR__),
    'homeUrl' => '?r=home/index',
    'bootstrap' => ['log'],
    'layout'=>'app',
    'defaultRoute' => 'site/index',
    'aliases' => [
        '@alerts' => '@app/modules/alerts',
        '@bike-keeper' => '@app/modules/bikeKeeper',
        '@events' => '@app/modules/events',
        '@lending' => '@app/modules/lending',
        '@renting' => '@app/modules/renting',
        '@routes' => '@app/modules/routes',
    ],
    'components' => [
        'assetManager' => [
            'appendTimestamp' => true,
            'assetMap' => [
                'mapbox.js' => 'https://api.mapbox.com/mapbox.js/v2.4.0/mapbox.js',
                'mapbox.css' => 'https://api.mapbox.com/mapbox.js/v2.4.0/mapbox.css',
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'oQAYahdFAVD7js-I0ykkVB4czUvmh6XT',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            //'identityClass' => 'app\models\User',
            'identityClass' => 'app\modules\user\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'i18n'=>[
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    //'sourceLanguage' => 'pt-US',
                    'sourceLanguage' => 'pt-BR',
                    'targetLanguage' => 'pt-BR',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'modules' => [
            'settings' => [
                'class' => 'app\modules\settings\Settings',
            ],
            'user' => [
                'class' => 'app\modules\user\User',
            ],
            'alerts' => [
                'class' => 'app\modules\alerts\Alerts',
            ],
            'bike-keeper' => [

                'class' => 'app\modules\bikeKeeper\BikeKeeper',

            ],
            'lending' => [

                'class' => 'app\modules\lending\Lending',

            ],
            'renting' => [

                'class' => 'app\modules\renting\Renting',

            ],
            'events' => [

                'class' => 'app\modules\events\Events',

            ],
            'routes' => [

                'class' => 'app\modules\routes\Routes',

            ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
