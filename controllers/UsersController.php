<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\Users;

/**
 * Default controller for the `users` module
 */
class UsersController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => [
                                        'index', 
                                        'friends-search',
                                        'get-friends',
                                        'set-online',
                                        'online',
                                        'challange',
                                        'update',
                            ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get', 'post'],
                    'set-online'=>['post'],
                    'online'=>['get'],
                    'challange'=>['get','post'],
                    'update'=>['post'],
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
            'set-online' => [
                'class'=>'app\controllers\users\SetOnlineAction',
            ],
            'online' => [
                'class'=>'app\controllers\users\OnlineAction',
            ],
            'challange' => [
                'class'=>'app\controllers\users\ChallangeAction',
            ],
            'update' => [
                'class'=>'app\controllers\users\UpdateAction',
            ],
        ];        
    }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
}
