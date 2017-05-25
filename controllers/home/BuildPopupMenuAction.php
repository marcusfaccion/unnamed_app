<?php
namespace app\controllers\home;

use yii\base\Action;

class BuildPopupMenuAction extends Action
{
    public function run($nav=null)
    {
        return $this->controller->renderAjax('build-popup-menu'.((bool)$nav?'-nav':''));
    }
}