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
                                        'active-bike-keepers',
                                        'bike-keeper-nonexistence-users',
                                        'delete-user-bike-keeper-nonexistence',
                                        'begin',
                                        'form',
                                        'render-popup',
                                        'render-popup-readonly',
                                        'get-feature',
                                        'get-features',
                                        'multimideas',
                                        'get-user-features',
                                        'create',
                                        'disable',
                                        'ilike',
                                        'idislike',
                                        'not-exists',
                                        'exists',
                                        'update',
                                        'used-capacity',
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
                    'active-bike-keepers' => ['get'],
                    'bike-keeper-nonexistence-users'=> ['get'],
                    'delete-user-bike-keeper-nonexistence' =>['post'],
                    'begin' => ['get'],
                    'get-feature'=>['get'],
                    'get-features'=>['get'],
                    'multimideas'=>['get'],
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
                    'exists' => ['post'],
                    'used-capacity' => ['post'],
                    'update' => ['post'],
                    'on' => ['post'],
                    'off' => ['post']
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
            'bike-keeper-nonexistence-users'=>[
                'class'=>'app\controllers\bikeKeepers\BikeKeeperNonexistenceUsersAction',
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
             'get-feature' => [
                'class'=>'app\controllers\bikeKeepers\GetFeatureAction',
            ],
             'get-features' => [
                'class'=>'app\controllers\bikeKeepers\GetFeaturesAction',
            ],
             'multimideas' => [
                'class'=>'app\controllers\bikeKeepers\MultimideasAction',
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
            'on' => [
                'class'=>'app\controllers\bikeKeepers\OnAction',
            ],
            'off' => [
                'class'=>'app\controllers\bikeKeepers\OffAction',
            ],
            'not-exists' => [
                'class'=>'app\controllers\bikeKeepers\NotExistsAction',
            ],
            'update' => [
                'class' => 'app\controllers\bikeKeepers\UpdateAction',
            ],
            'used-capacity' => [
                'class' => 'app\controllers\bikeKeepers\UsedCapacityAction',
            ],
            'delete-user-bike-keeper-nonexistence' => [
                'class'=>'app\controllers\bikeKeepers\DeleteUserBikeKeeperNonexistenceAction',
            ],
        ];
    }
    
    /**
     * Renderiza a tabela de bicicletários ativos do usuário
     * @return string
     */
    public function actionActiveBikeKeepers($user_id, $tab='default')
    {
        $user = Users::findOne($user_id);
        $config = [
            'li.active-bike-keepers'=>['class'=>($tab=='default'?'active':'')],
            'li.active-bike-keepers-problem'=>['class'=>($tab=='problem'?'active':'')],
            'tab-pane.active-bike-keepers'=>['class'=>($tab=='default'?'in active':'')],
            'tab-pane.active-bike-keepers-problem'=>['class'=>($tab=='problem'?'in active':'')],
        ];
        return $this->renderPartial('_bike_keepers_manager',['user'=>$user, 'config'=>$config]);
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
        
        $config = [
            'li.active-bike-keepers'=>['class'=>'active'],
            'li.active-bike-keepers-problem'=>['class'=>''],
            'tab-pane.active-bike-keepers'=>['class'=>'in active'],
            'tab-pane.active-bike-keepers-problem'=>['class'=>''],
        ];
        
        return $this->render('index', ['user'=>$user, 'config'=>$config]);
    }
}
