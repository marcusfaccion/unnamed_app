<?php
use yii\bootstrap\Html;
?>
 <div>
  <!-- Nav tabs -->
  <ul  id='bike-keepers-nav-tabs' class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active" id='bike-keepers-nav-tab-active'>
          <a href="#active-bike-keepers" aria-controls="active-bike-keepers" role="tab" data-toggle="tab">Ativos <span class="badge"><?=count($user->activeBikeKeepers)>0?count($user->activeBikeKeepers):null?></span></a>
      </li>
    <li role="presentation" bike-keepers-nav-tab-problem>
        <a href="#active-nonbike-keepers" aria-controls="active-nonbike-keepers" role="tab" data-toggle="tab">Problemas <span class="badge"><?=count($user->activeNonexistentBikeKeepers)>0?count($user->activeNonexistentBikeKeepers):null?></span></a>
    </li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
      <div role="tabpanel" class="tab-pane fade in active" id="active-bike-keepers">
          <?=Yii::$app->controller->renderPartial('_active_bike_keepers',['bike_keepers'=>$user->activeBikeKeepers])?>
      </div>
      <div role="tabpanel" class="tab-pane fade" id="active-nonbike-keepers">
          <?=Yii::$app->controller->renderPartial('_active_nonbike_keepers',['non_bike_keepers'=>$user->activeNonexistentBikeKeepers])?>
      </div>
  </div>
</div>
 