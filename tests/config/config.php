<?php
/**
 * Application configuration shared by all test types
 */
return [
    'language' => 'en-US',
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\faker\FixtureController',
            'fixtureDataPath' => '@tests/fixtures',
            'templatePath' => '@tests/templates',
            'namespace' => 'tests\codeception\fixtures',
        ],
    ],
    'components' => [
        'db' => [
            #'dsn' => 'mysql:host=localhost;dbname=yii2_basic_tests',
            'dsn' => 'pgsql:host=localhost;port=5432;dbname=rastreador_de_entrega_rt',
            'username' => 'postgres',
            'password' => '123456',
            'charset' => 'utf8',

        ],
        'mailer' => [
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
    ],
];
