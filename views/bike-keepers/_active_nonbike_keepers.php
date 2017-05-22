<?php
use yii\helpers\Url;
use yii\bootstrap\Html;
//Url::to(['bike_keepers/test', 'id'=>$bike_keeper->id])
?>

<div class="panel-group top-buffer-3" id="nonbike-keeper-accordion" role="tablist" aria-multiselectable="true">
<?php foreach ($non_bike_keepers as $bike_keeper): ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="bike-keeper-heading-<?=$bike_keeper->id?>">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#nonbike-keeper-accordion" href="#collapse-bike-keeper-<?=$bike_keeper->id?>" aria-expanded="true" aria-controls="collapse-bike-keeper-<?=$bike_keeper->id?>">
            O bicicletário <span class="text-primary"><strong>#<?=$bike_keeper->id?></strong></span> cadastrado em <?=Yii::$app->formatter->asDate($bike_keeper->created_date)?> foi criticado <strong><?=Yii::t('app','{n,plural,=1{uma vez} other{# vezes}}',['n'=>count($bike_keeper->nonExistence)])?></strong> como não existente. 
        </a>
      </h4>
    </div>
    <div id="collapse-bike-keeper-<?=$bike_keeper->id?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-bike-keeper-<?=$bike_keeper->id?>">
      <div class="panel-body">
              
          <a id='nonbike-keeper-<?=$bike_keeper->id?>-btn-viewonmap' data-toggle="tooltip" data-placement="left" title="Ver no mapa" role="button" class="btn btn-xs btn-default nonbike-keeper-view-on-map" data-original-title="Ver no mapa"><span class="text-primary glyphicon glyphicon-globe"></span></a>
          <a id='nonbike_keeper-<?=$bike_keeper->id?>-btn-disable' data-toggle="tooltip" data-placement="left" title="Desativar bicicletário" role="button" class="btn btn-xs btn-default nonbike-keeper-disable" data-original-title="Desativar bike-keepera"><span class="text-danger glyphicon glyphicon-remove-circle"></span></a>
          <span data-toggle="tooltip" data-placement="top" title="Ver informantes" role="button" class="btn btn-xs btn-default nonbike-keeper-view-users" data-original-title="Ver informantes">
            <a id='nonbike_keeper-<?=$bike_keeper->id?>-btn-users' data-toggle="modal" data-target="#bike_keepers_view_users_modal"><span class="text-muted glyphicon glyphicon-user"></span></a>
          </span>
          <?=Html::hiddenInput($bike_keeper->formName().'[id]', $bike_keeper->id);?> 
      </div>
      </div>
    </div>
<?php endforeach; ?>
</div>