<?php
use yii\bootstrap\Html;
?>
 <div>
  <!-- Nav tabs -->
  <ul  id='alerts-nav-tabs' class="nav nav-tabs" role="tablist">
      <li role="presentation" class="<?=$config['li.active-alerts']['class']?> active-alerts" id='alerts-nav-tab-active'>
          <a href="#active-alerts" aria-controls="active-alerts" role="tab" data-toggle="tab">Ativos <span class="badge"><?=count($user->activeAlerts)>0?count($user->activeAlerts):null?></span></a>
      </li>
    <li role="presentation" class="<?=$config['li.active-alerts-problem']['class']?> active-alerts-problem" id="alerts-nav-tab-problem">
        <a href="#active-nonalerts" aria-controls="active-nonalerts" role="tab" data-toggle="tab">Problemas <span class="badge"><?=count($user->activeNonexistentAlerts)>0?count($user->activeNonexistentAlerts):null?></span></a>
    </li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
      <div role="tabpanel" class="tab-pane fade <?=$config['tab-pane.active-alerts']['class']?> active-alerts" id="active-alerts">
          <?=Yii::$app->controller->renderPartial('_active_alerts',['alerts'=>$user->activeAlerts])?>
      </div>
      <div role="tabpanel" class="tab-pane fade <?=$config['tab-pane.active-alerts-problem']['class']?> active-alerts-problem" id="active-nonalerts">
          <?=Yii::$app->controller->renderPartial('_active_nonalerts',['non_alerts'=>$user->activeNonexistentAlerts])?>
      </div>
  </div>
</div>
 