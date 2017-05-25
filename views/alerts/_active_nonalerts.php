<?php
use yii\helpers\Url;
use yii\bootstrap\Html;
//Url::to(['alerts/test', 'id'=>$alert->id])
?>
<div class="panel-group top-buffer-3" id="nonalert-accordion" role="tablist" aria-multiselectable="true">
<?php foreach ($non_alerts as $alert): ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="alert-heading-<?=$alert->id?>">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#nonalert-accordion" href="#collapse-alert-<?=$alert->id?>" aria-expanded="true" aria-controls="collapse-alert-<?=$alert->id?>">
            O alerta <span class="text-primary"><strong>#<?=$alert->id?></strong></span> do tipo <strong><?=$alert->type->description?></strong> cadastrado em <?=Yii::$app->formatter->asDate($alert->created_date)?> foi criticado <strong><?=Yii::t('app','{n,plural,=1{uma vez} other{# vezes}}',['n'=>count($alert->nonExistence)])?></strong> como não existente. 
        </a>
      </h4>
    </div>
    <div id="collapse-alert-<?=$alert->id?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-alert-<?=$alert->id?>">
      <div class="panel-body">
              
          <a id='nonalert-<?=$alert->id?>-btn-viewonmap' data-toggle="tooltip" data-placement="left" title="Ver no mapa" role="button" class="btn btn-xs btn-default nonalert-view-on-map" data-original-title="Ver no mapa"><span class="text-primary glyphicon glyphicon-globe"></span></a>
          <a id='nonalert-<?=$alert->id?>-btn-disable' data-toggle="tooltip" data-placement="left" title="Desativar alerta" role="button" class="btn btn-xs btn-default nonalert-disable" data-original-title="Desativar alerta"><span class="text-danger glyphicon glyphicon-remove-circle"></span></a>
          <span data-toggle="modal" data-target="#alert_view_users_modal">
            <a id='nonalert-<?=$alert->id?>-btn-users' data-toggle="tooltip" data-placement="left" title="Ver informantes" role="button" class="btn btn-xs btn-default nonalert-view-users" data-original-title="Ver informantes"><span class="text-muted glyphicon glyphicon-user"></span></a>
          </span>
          <?=Html::hiddenInput($alert->formName().'[id]', $alert->id);?> 
          
      </div>
      </div>
    </div>
<?php endforeach; ?>
<?php if(count($non_alerts)==0):?>
<div class="alert alert-warning alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Fechar"><span aria-hidden="true">×</span></button>
                <strong>Não há problemas reportados.</strong></div>

<?php endif;?>
</div>