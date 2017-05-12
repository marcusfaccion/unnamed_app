<?php

namespace app\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\BikeKeepers;

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
                        'actions' => [
                                        'index', 
                                        'begin',
                                        'form',
                                        'render-popup',
                                        'get-features',
                                        'create',
                                        'disable',
                                        'ilike',
                                        'idislike',
                                        'not-exists',
                                        'exists'
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
                    'begin' => ['get'],
                    'get-features'=>['get'],
                    'render-popup' => ['get'],
                    'form' => ['get'],
                    'create' => ['post'],
                    'logout' => ['post'],
                    'disable' => ['post'],
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
                'class' => 'app\controllers\bikeKeepers\CreateAction',
            ],
            'form' => [
                'class' => 'app\controllers\bikeKeepers\FormAction',
            ],
            'render-popup' => [
                'class'=>'app\controllers\bikeKeepers\RenderPopupAction',
            ],
             'get-features' => [
                'class'=>'app\controllers\bikeKeepers\GetFeaturesAction',
            ],
            'disable' => [
                'class' => 'app\controllers\bikeKeepers\DisableAction',
            ],
             'ilike' => [
                'class'=>'app\controllers\bikeKeepers\IlikeAction',
            ],
             'idislike' => [
                'class'=>'app\controllers\bikeKeepers\IdislikeAction',
            ],
            'exists' => [
                'class'=>'app\controllers\bikeKeepers\ExistsAction',
            ],
            'not-exists' => [
                'class'=>'app\controllers\bikeKeepers\NotExistsAction',
            ],
        ];
    }
    
    /**
     * Renderiza a tela inicial do modal BikeKeepers (guardador de bike)
     * @return string
     */
    public function actionBegin()
    {
        $bike_keeper = new BikeKeepers(['scenario'=>BikeKeepers::SCENARIO_CREATE]);
        $bike_keeper->user_id = Yii::$app->user->identity->id;
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
