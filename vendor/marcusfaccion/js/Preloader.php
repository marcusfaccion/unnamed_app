<?php

namespace marcusfaccion\js;
use yii\web\JsExpression;

/**
 * Description of TimeZone
 *
 * @author Marcus
 */
class Preloader {
    /**
     * Renderiza a função JavaScript preloader.hide(), precisa que o script js preloader esteja carregado no DOM 
     * @param string $elem_id
     * @param string $name
     * @param string $size
     * @param string $callback
     * @return JsExpression
     */
   static function hide($elem_id, $name, $size, $callback='function{}'){
       return new JsExpression(
               "preloader.hide('$elem_id', '$name', '$size', $callback);", []
       );
   }
}
