<?php
namespace app\controllers\home;

use yii\base\Action;

class BuildPopupMenuAction extends Action
{
    public function run()
    {
        return $this->controller->renderAjax('build-popup-menu');
    }
}