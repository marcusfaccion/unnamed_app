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
                                        'render-popup-readonly',
                                        'form',
                                        'get-features',
                                        'create',
                                        'view',
                                        'update',
                                        'active-alerts',
                                        'active-non-alerts',
                                        'delete-user-alert-nonexistence',
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
                    'render-popup-readonly' => ['get'],
                    'view' => ['get','post'],
                    'form' => ['get'],
                    'create' => ['post'],
                    'update' => ['post'],
                    'disable' => ['post'],
                    'active-alerts' => ['get'],
                    'active-non-alerts' => ['get'],
                    'delete-user-alert-nonexistence'=>['post'],
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
             'render-popup-readonly' => [
                'class'=>'app\controllers\alerts\RenderPopupReadonlyAction',
            ],
             'get-features' => [
                'class'=>'app\controllers\alerts\GetFeaturesAction',
            ],
             'get-user-features' => [
                'class'=>'app\controllers\alerts\GetUserFeaturesAction',
            ],
            'alert-nonexistence-users'=>[
                'class'=>'app\controllers\alerts\AlertNonexistenceUsersAction',
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
            'delete-user-alert-nonexistence' => [
                'class'=>'app\controllers\alerts\DeleteUserAlertNonexistenceAction',
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
        
        $config = [
            'li.active-alerts'=>['class'=>'active'],
            'li.active-alerts-problem'=>['class'=>''],
            'tab-pane.active-alerts'=>['class'=>'in active'],
            'tab-pane.active-alerts-problem'=>['class'=>''],
        ];
        
        return $this->render('index', ['user'=>$user, 'config'=>$config]);
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
    /**
     * Renderiza a tabela de alertas ativos do usuário
     * @return string
     */
    public function actionActiveAlerts($user_id, $tab='default')
    {
        $user = Users::findOne($user_id);
        
        $config = [
            'li.active-alerts'=>['class'=>($tab=='default'?'active':'')],
            'li.active-alerts-problem'=>['class'=>($tab=='problem'?'active':'')],
            'tab-pane.active-alerts'=>['class'=>($tab=='default'?'in active':'')],
            'tab-pane.active-alerts-problem'=>['class'=>($tab=='problem'?'in active':'')],
        ];
        
        return $this->renderPartial('_alerts_manager',['user'=>$user, 'config'=>$config]);
    }
    /**
     * Renderiza a tabela de alertas ativos com problemas reportados por usuários
     * @return string
     */
    public function actionActiveNonAlerts($user_id)
    {
        $user = Users::findOne($user_id);
        return $this->renderPartial('_active_nonalerts',['non_alerts'=>$user->activeNonexistentAlerts]);
    }
    
    public function beforeAction($action)
    {
        parent::beforeAction($action);
        //Index Bootstrap
        if(in_array($this->action->id, ['index','active-alerts', 'active-non-alerts'])){
            //Chamada de Store Procedure que desativa alertas vencidos 
            //$row_count = Yii::$app->db->createCommand("SELECT st_alerts_check_duration();")->execute();        
            $alerts_count = (new \yii\db\Query)->select('st_disable_alerts()')->scalar();
            $alerts_count += (new \yii\db\Query)->select('st_alerts_check_duration()')->scalar();
        }
        return true;
    }
 
}
