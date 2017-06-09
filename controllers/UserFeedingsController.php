<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;


/**
 * Default controller for the `users` module
 */
class UserFeedingsController extends Controller
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
                                        'interaction-panel', 
                                        'more', 
                            ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'interaction-panel' => ['get', 'post'],
                    'more' => ['get'],
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
            'interaction-panel' => [
                'class'=>'app\controllers\userFeedings\InteractionPanelAction',
            ],
            'more' => [
                'class'=>'app\controllers\userFeedings\MoreAction',
            ],
        ];        
    }
    
}
