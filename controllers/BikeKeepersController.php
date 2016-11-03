<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class BikeKeepersController extends Controller
{
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['index', 'begin', 'form', 'create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get', 'post'],
                    'begin' => ['get'],
                    'form' => ['get'],
                    'create' => ['post'],
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'create' => [
                'class' => 'app\controllers\bikeKeepers\CreateAction.php',
            ],
            'form' => [
                'class' => 'app\controllers\bikeKeepers\FormAction.php',
            ]
        ];
    }
    
    /**
     * Renderiza a tela inicial do modal BikeKeepers (guardador de bike)
     * @return string
     */
    public function actionBegin()
    {
        $bike_keeper = new \app\models\BikeKeepers();
        return $this->renderAjax('begin', ['bike_keeper' => $bike_keeper]);
    }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->renderFile('index');
    }
}
