<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;


class PanelController extends Controller
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
                                        'online-friends'
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
                    'online-friends' => ['get'],
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
           'online-friends' => [
                'class' => 'app\controllers\panel\OnlineFriendsAction',
            ],
//            'create' => [
//                'class' => 'app\controllers\messages\CreateAction',
//            ],
        ];
    }

    public function beforeAction($action)
    {
        parent::beforeAction($action);

        return true;
    }
    
    public function actionIndex()
    {
        $user = \app\models\Users::findOne(Yii::$app->user->identity->id);
        
        // Obtendo os itens do feeding do usuário composto pelos compartilhamentos de seus amigos e os seus próprios
        $feedings = \app\models\UserFeedings::find()->where('user_id in (select * from user_'.$user->id.'_friends_id)')
                ->orWhere(['user_id'=>$user->id])
                ->orderBy('created_date desc')
                ->limit(7)
                ->all();
        return $this->render('index', ['user'=>$user, 'user_id2'=>-1, 'feedings'=>$feedings]);
    }

}
