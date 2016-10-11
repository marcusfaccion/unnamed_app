<?php
namespace app\modules\alerts\controllers\widget;

use yii\base\Action;

class FormAction extends Action
{
    public function run($type_id)
    {
        return $this->controller->renderPartial('_alert-widget-form.part.php');
    }
}