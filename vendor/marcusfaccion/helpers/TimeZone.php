<?php

namespace marcusfaccion\helpers;

/**
 * Description of TimeZone
 *
 * @author Marcus
 */
class TimeZone {
   static function appInfo(){
       return '<p>PHP date: '. date('d-m-Y H:i:s').' <p> App formated date: '.\Yii::$app->formatter->asDatetime(date('d-m-Y H:i:s'),'long').' <p>AppTZ: '.\Yii::$app->timeZone.' <p>AppFormatterTZ '.\Yii::$app->formatter->timeZone.' <p>AppFormatterDefaultTZ '.\Yii::$app->formatter->defaultTimeZone;
   }
}
