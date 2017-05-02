<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Alerts;
use app\models\Users;

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
                                        'index',
                                        'begin',
                                        'render-popup',
                                        'form',
                                        'get-features',
                                        'create',
                                        'view',
                                        'update',
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
                    'index'=>['get'],
                    'get-features'=>['get'],
                    'get-user-features'=>['get'],
                    'begin' => ['get'],
                    'render-popup' => ['get'],
                    'view' => ['get','post'],
                    'form' => ['get'],
                    'create' => ['post'],
                    'update' => ['post'],
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
            'update' => [
                'class'=>'app\controllers\alerts\UpdateAction',
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
             'get-user-features' => [
                'class'=>'app\controllers\alerts\GetUserFeaturesAction',
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
     * Renderiza a página de gerenciamento de alertas do usuário
     * @return string
     */
    public function actionIndex()
    {
        $user = Users::findOne(Yii::$app->user->identity->id);
        return $this->render('index', ['user'=>$user]);
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
