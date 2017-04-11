<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'aplicacao-ACC',
    'name' => 'BIKE SOCIAL',	
    'language' => 'pt-BR',
    'sourceLanguage' => 'pt-BR',
    'basePath' => dirname(__DIR__),
    'homeUrl' =>'home',
    'bootstrap' => ['log'],
    'layout'=>'app',
    'timeZone' => 'UTC', //php executes date_default_timezone_set('UTC')
    'defaultRoute' =>'site',
    'aliases' => [
        '@webroot' => '@app/web',
        '@settings' => '@app/modules/settings',
        '@api' => '@app/modules/api',
        '@marcusfaccion' => '@vendor/marcusfaccion',
        '@users_dir_path' => '@webroot/user-contents',
        '@users_dir' => 'user-contents',
        '@bike_keepers_dir_path'=>'@webroot/bike-keepers',
        '@bike_keepers_dir'=>'bike-keepers',
    ],
    'components' => [
        'assetManager' => [
            'appendTimestamp' => true,
            'assetMap' => [
//                'mapbox.js' => 'https://api.mapbox.com/mapbox.js/v2.4.0/mapbox.js',
//                'mapbox.css' => 'https://api.mapbox.com/mapbox.js/v2.4.0/mapbox.css',
//                'mapbox.directions.js' => 'https://api.mapbox.com/mapbox.js/plugins/mapbox-directions.js/v0.4.0/mapbox.directions.js',
//                'mapbox.directions.css' => 'https://api.mapbox.com/mapbox.js/plugins/mapbox-directions.js/v0.4.0/mapbox.directions.css',
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
            'dateFormat' => 'dd-MM-yyyy', // ICU format
            'timeFormat' => 'HH:mm:ss',
            'datetimeFormat' => 'dd-MM-yyyy HH:mm:ss',
            'decimalSeparator' => ',',
            'locale' => 'pt-BR',
            'numberFormatterOptions' => [
                NumberFormatter::MIN_FRACTION_DIGITS => 0,
                NumberFormatter::MAX_FRACTION_DIGITS => 2,
            ],
           'timeZone' => 'America/Sao_Paulo', // Se não definido, defaultTimeZone será utilizado
            //'defaultTimeZone'=>'UTC', // o default é UTC
            'thousandSeparator' => ' ',
        ],
        'i18n'=>[
            'translations' => [
                'core' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@yii/messages',
                    //'sourceLanguage' => 'en-US',
                ],
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'pt-BR',
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
        'response' => [
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG, // use "pretty" output in debug mode
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                    //'encodeOptions' => JSON_UNESCAPED_SLASHES,
                ],
            ],
        ],
        'security' => [
            'class' => 'marcusfaccion\security\Security',
        ],

        'user' => [
            //'identityClass' => 'app\models\User',
            'identityClass' => 'app\models\Users',
            'enableAutoLogin' => true,
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
    ],
    'modules' => [
            'settings' => [
                'class' => 'app\modules\settings\Settings',
            ],
            'api' => [
                'class' => 'app\modules\api\Api',
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
