<?php

namespace app\Controllers;

class MessagesController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
