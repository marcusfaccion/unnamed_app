<?php
use yii\bootstrap\Html;
?>
 <?php if(count($alerts)>0):?>
 <div class="col-lg-12 col-xs-12 panel panel-default top-buffer-2">
  <!-- Default panel contents -->
  <div class="panel-heading"><strong class="text-primary">Ações em massa:</strong></div>
  <div class="panel-body">
      <div class="row">
          <div class="col-lg-6 col-xs-12 left-pbuffer-0 right-pbuffer-0">
              <div class="row">
                  <div class="col-lg-2 col-xs-4 right-pbuffer-0">
                      <a role='button' class='btn btn-xs btn-default alert-select-all'>Marcar todos</a>
                  </div>
                  <div class="col-lg-2 col-xs-4 left-pbuffer-0 right-pbuffer-0">
                      <a role='button' class='btn btn-xs btn-default alert-noselect-all'>Desmarcar todos</a>
                  </div>
                  <div class="col-lg-2 col-xs-3 left-pbuffer-0 right-pbuffer-0 left-buffer-2">
                      <?=Html::button('<span class="glyphicon glyphicon-remove-circle"></span> Desativar', ['class'=>'btn btn-xs btn-danger alert-disable-all'])?>
                  </div>
              </div>
        </div>
      </div>
  </div>
 </div>

<div class="top-buffer-2">
      <table id='alerts-table' class="table table-hover table-responsive"> 
    <thead> 
        <tr> 
            <th>#</th>
            <th>Data de envio</th>
            <th>Tipo</th>
            <th>Descrição</th>
            <th>Endereço</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
<?php $i=1;?>        
<?php foreach ($alerts as $alert): ?>
        <tr class="data"> 
            <th scope="row"><?=Html::checkbox($alert->formName().'[][id]', false, ['value'=>$alert->id])?> <?=$i?></th> 
            <td class="text-muted" data-toggle="tooltip" data-placement="right" title="<?=Yii::$app->formatter->asDate($alert->created_date)?>"><strong><?=Yii::$app->formatter->asRelativeTime($alert->created_date)?></strong></td>
            <td class="text-muted"><strong><?=Html::img('images/icons/marker/'.$alert->type->markerIcon,[]);?> <?=$alert->type->description?></strong></td>
            <td class="text-muted"><strong><?=$alert->description?></strong></td>
            <td class="text-muted"><strong><?=$alert->address?></strong></td>
            <td>
                <a data-toggle="tooltip" data-placement="left" title="Editar" role='button' class='btn btn-xs btn-default alert-update'><span class="glyphicon glyphicon-edit"></span></a>
                <a data-toggle="tooltip" data-placement="left" title="Desativar" role='button' class='btn btn-xs btn-default alert-disable-one'><span class="text-danger glyphicon glyphicon-remove-circle"></span></a>
                <a data-toggle="tooltip" data-placement="left" title="Ver no mapa" role='button' class='btn btn-xs btn-default alert-view-on-map'><span class="text-primary glyphicon glyphicon-globe"></span></a>
                <?php // Html::hiddenInput($alert->formName().'[id]', $alert->id)?>
            </td>
        </tr> 
<?php $i++;?>
<?php endforeach; ?>
    </tbody> 
</table>
</div>
<?php else:?>
<div class="top-buffer-2 ">
    
    <div class="alert alert-warning alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Fechar"><span aria-hidden="true">×</span></button>
                    <strong>Não há alertas cadastrados por enquanto.</strong></div>
            
</div>
<?php endif; ?>