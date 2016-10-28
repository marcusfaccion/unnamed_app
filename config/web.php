<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'aplicacao-ACC',
    'name' => 'Apicação Colaborativa para Ciclistas',	
    'language' => 'pt-BR',
    'sourceLanguage' => 'pt-BR',
    'basePath' => dirname(__DIR__),
    'homeUrl' => '?r=home/index',
    'bootstrap' => ['log'],
    'layout'=>'app',
    'timeZone' => 'UTC',
    'defaultRoute' => 'site/index',
    'aliases' => [
        '@alerts' => '@app/modules/alerts',
        '@bike-keeper' => '@app/modules/bikeKeeper',
        '@events' => '@app/modules/events',
        '@lending' => '@app/modules/lending',
        '@marcusfaccion' => '@vendor/marcusfaccion',
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
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => require(__DIR__ . '/db.php'),
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'formatter' => [
            'currencyCode' => 'BRL',
            'dateFormat' => 'dd-MM-yyyy',
            'timeFormat' => 'HH:mm:ss',
            'datetimeFormat' => 'dd-MM-yyyy HH:mm:ss',
            'decimalSeparator' => ',',
            'locale' => 'pt-BR',
            'numberFormatterOptions' => [
                NumberFormatter::MIN_FRACTION_DIGITS => 0,
                NumberFormatter::MAX_FRACTION_DIGITS => 2,
            ],
            'timeZone' => 'America/Sao_Paulo',
            'thousandSeparator' => ' ',
        ],
        'i18n'=>[
            'translations' => [
                'yii' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-US',
                ],
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    //'basePath' => '@app/messages',
                    //'sourceLanguage' => 'pt-BR',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
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
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'oQAYahdFAVD7js-I0ykkVB4czUvmh6XT',
        ],
        
        'user' => [
            //'identityClass' => 'app\models\User',
            'identityClass' => 'app\modules\user\models\Users',
            'enableAutoLogin' => true,
        ],
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
