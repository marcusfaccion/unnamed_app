<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class AlertsController extends Controller
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
                                        'begin',
                                        'render-popup',
                                        'form',
                                        'get-features',
                                        'create'
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
                    'get-features'=>['get'],
                    'begin' => ['get'],
                    'render-popup' => ['get'],
                    'form' => ['get'],
                    'create' => ['post']
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
                'class'=>'app\controllers\alerts\CreateAction',
            ],
            'form' => [
                'class'=>'app\controllers\alerts\FormAction',
            ],
             'render-popup' => [
                'class'=>'app\controllers\alerts\RenderPopupAction',
            ],
             'get-features' => [
                'class'=>'app\controllers\alerts\GetFeaturesAction',
            ],
            
        ];
    }
    
    /**
     * Renderiza o menu de tipos de alertas
     * @return string
     */
    public function actionBegin()
    {
        return $this->renderAjax('begin');
    }
 
}
