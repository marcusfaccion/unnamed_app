<?php

namespace app\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\BikeKeepers;
use app\models\Users;

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
                                        'render-popup-readonly',
                                        'get-features',
                                        'get-user-features',
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
                    'get-user-features'=>['get'],
                    'render-popup' => ['get'],
                    'render-popup-readonly' => ['get'],
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
            'render-popup-readonly' => [
                'class'=>'app\controllers\bikeKeepers\RenderPopupReadonlyAction',
            ],
             'get-features' => [
                'class'=>'app\controllers\bikeKeepers\GetFeaturesAction',
            ],
             'get-user-features' => [
                'class'=>'app\controllers\bikeKeepers\GetUserFeaturesAction',
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
        $user = Users::findOne(Yii::$app->user->identity->id);
        return $this->render('index', ['user'=>$user]);
    }
}
