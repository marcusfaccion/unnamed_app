<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace marcusfaccion\security;

use Yii;
use yii\base\Security as YiiSecurity;

/**
 * Description of Security
 *
 * @author Marcus
 */
class Security extends YiiSecurity{
    
    function getAppSecret(){
        return isset(Yii::$app->params['app_secret'])?Yii::$app->params['app_secret']:'';
    }
    
}
