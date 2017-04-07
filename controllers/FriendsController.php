<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Users;

/**
 * Default controller for the `users` module
 */
class FriendsController extends Controller
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
                                        'add',
                                        'friends-search',
                                        'get-friends'
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
                    'add' => ['post'],
                    'friends-search' => ['get'],
                    'get-friends' => ['post'],
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
            'add' => [
                'class' => 'app\controllers\friends\AddAction',
            ],
        ];
    }
    /**
     * Renderiza a tela inicial do modal Friends
     * @return string
     */
    public function actionBegin()
    {
        $user = Users::findOne(Yii::$app->user->identity->id);
        $friendshipRequests = $user->friendshipRequests;
        return $this->renderAjax('begin', ['user' => $user]);
    }
    
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    /**
     * Executa busca textual de usuários para autocomplete do modal friends search
     * @return string
     */
    public function actionFriendsSearch($term)
    {
        $terms = '';
        $term = trim($term);
        if(isset($terms) && !empty($term)){
            $terms = explode(' ',$term);
            if(count($terms)==1)
                $terms[1] = '';
            
            //ilike funciona para o postgres executando uma busca case insensitive, caso já tenha solicitado um usuário este não será achado 
           $users = Users::find()->where(['ilike', 'full_name', [trim($terms[0]), trim($terms[1])]])->andWhere('id not in (select * from user_'.Yii::$app->user->identity->id.'_friends_id)')->andWhere('id<>'.Yii::$app->user->identity->id)->all();
            
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $this->renderPartial('_friends_search', ['users'=>$users]);
            Yii::$app->end(0);
        }
            Yii::$app->end(0);
    }
    /**
     * Executa busca textual por usuários para serem impressos no modal friends search
     * @return string
     */
    public function actionGetFriends()
    { 
        $terms = '';
        $term = trim(Yii::$app->request->post('Users')['full_name']);
        if(isset($term) && !empty(trim($term))){
            $terms = explode(' ',$term);
            if(count($terms)==1)
                $terms[1] = '';
            //ilike funciona para o postgres executando uma busca case insensitive 
            $users = Users::find()->where(['ilike', 'full_name', [trim($terms[0]), trim($terms[1])]])->andWhere('id not in (select * from user_'.Yii::$app->user->identity->id.'_friends_id)')->andWhere('id<>'.Yii::$app->user->identity->id)->all();
            return $this->renderAjax('_friends_subscribe', ['users'=>$users]);
            Yii::$app->end(0);
        }
            Yii::$app->end(0);
    }
}
