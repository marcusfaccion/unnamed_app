<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Users;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    
    public $layout = 'site';
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get', 'post'],
                    'logout' => ['post'],
                    'signup' => ['post'],
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
        ];
    }
    
    public function actionAbout()
    {
        return $this->render('about');
    }
    
     public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }
    
    public function actionIndex()
    {
        if(!Yii::$app->user->isGuest){
            $this->redirect(Url::to(['home/index']));
        }
        return $this->render('index', ['user'=>new Users(['scenario'=>Users::SCENARIO_CREATE])]);
    }
    
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack([Yii::$app->homeUrl]);
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return Yii::$app->getResponse()->redirect([Yii::$app->defaultRoute]);
    }  
    
    public function goHome() {
        return Yii::$app->getResponse()->redirect([Yii::$app->homeUrl]);
    }

    public function actionSignup() {
        $user = new Users(['scenario'=>Users::SCENARIO_CREATE]);
        $user->attributes = Yii::$app->request->post('Users');
        $user->avatar_file = UploadedFile::getInstance($user, 'avatar_file');
        if($user->upload()){
            $user->save();
            Yii::$app->session->setFlash('signup-success','Cadastro feito com sucesso!');
            return $this->redirect(Url::to(['site/index','#'=>'site-signup-form']));
        }            
        
//        if(Yii::$app->request->isAjax)
//            return $this->renderAjax('');
        
        return $this->render('index', ['user'=>$user]);
    }
}
