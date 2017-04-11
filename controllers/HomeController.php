<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class HomeController extends Controller
{   
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        //'controllers' => [],
                        'actions' => [
                                        'index',
                                        'build-popup-menu',
                                        'get-confirm-message'
                                    ],
                        'allow' => true,
                        'roles' => ['@'],
                        //'verbs' => ['GET','POST'],
                        //'ips' => [127.0.0.1, ::1],
                        'denyCallback' => function ($rule, $action) {
                            throw new \Exception('You are not allowed to access this page');
                        },
                        //'matchCallback' => []
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get', 'post'],
                    'logout' => ['post'],
                    'build-popup-menu'=>['get'],
                    'get-confirm-message'=>['post']
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
            'build-popup-menu' => [
                'class' => 'app\controllers\home\BuildPopupMenuAction',
            ],
            'get-confirm-message' => [
                'class' => 'app\controllers\home\GetConfirmMessageAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

}
