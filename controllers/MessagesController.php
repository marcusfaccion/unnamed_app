<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;


class MessagesController extends Controller
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
                                        'create',
                                        'conversation',
                                        'online-friends',
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
                    'create'=>['post'],
                    'conversation'=>['get'],
                    'online-friends'=>['get'],
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
                'class' => 'app\controllers\messages\CreateAction',
            ],
            'conversation' => [
                'class' => 'app\controllers\messages\ConversationAction',
            ],
        ];
    }

    public function beforeAction($action)
    {
        parent::beforeAction($action);

        return true;
    }
    
//    public function actionIndex()
//    {
//        $user = \app\models\Users::findOne(Yii::$app->user->identity->id);
//        return $this->render('index', ['user'=>$user, 'user_id2'=>-1]);
//    }

}
