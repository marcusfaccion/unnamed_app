<?php

namespace app\modules\alerts\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * Default controller for the `alerts` module
 */
class ItemController extends Controller
{
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['create','render-popup'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get', 'post'],
                    'render-popup' => ['get', 'post'],
                    'create' => ['get', 'post'],
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
                'class'=>'app\modules\alerts\controllers\item\CreateAction',
            ],
             'render-popup' => [
                'class'=>'app\modules\alerts\controllers\item\RenderPopupAction',
            ],
        ];
    }
    
    public function actionIndex(){
       return $this->renderContent("{$this->module->id} Index");
    }
}
