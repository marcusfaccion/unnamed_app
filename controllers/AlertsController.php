<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Alerts;

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
                                        'create',
                                        'view',
                                        'delete',
                                        'disable',
                                        'enable',
                                        'ilike',
                                        'idislike',
                                        'not-exists',
                                        'exists'
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
                    'view' => ['get','post'],
                    'form' => ['get'],
                    'create' => ['post'],
                    'delete' => ['post'],
                    'disable' => ['post'],
                    'enable' => ['post'],
                    'ilike' => ['post'],
                    'idislike' => ['post'],
                    'not-exists' => ['post'],
                    'exists' => ['post']
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
            'delete' => [
                'class'=>'app\controllers\alerts\DeleteAction',
            ],
            'disable' => [
                'class'=>'app\controllers\alerts\DisableAction',
            ],
            'enable' => [
                'class'=>'app\controllers\alerts\EnableAction',
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
             'ilike' => [
                'class'=>'app\controllers\alerts\IlikeAction',
            ],
             'idislike' => [
                'class'=>'app\controllers\alerts\IdislikeAction',
            ],
            'exists' => [
                'class'=>'app\controllers\alerts\ExistsAction',
            ],
            'not-exists' => [
                'class'=>'app\controllers\alerts\NotExistsAction',
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
    
    /**
     * Renderiza os dados de um alerta
     * @return string
     */
    public function actionView($id)
    {
        $alert = Alerts::findOne($id);
        return $this->render('view', ['alert'=>$alert]);
    }
 
}
