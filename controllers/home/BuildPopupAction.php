<?php
namespace app\controllers\home;

use yii\base\Action;

class BuildPopupAction extends Action
{
    public function run()
    {
        return $this->controller->renderPartial('_build-popup.part.php');
    }
}