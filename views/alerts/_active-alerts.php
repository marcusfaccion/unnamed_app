<?php
use yii\bootstrap\Html;
?>
 
 <div class="col-lg-12 col-xs-12 panel panel-default top-buffer-2">
  <!-- Default panel contents -->
  <div class="panel-heading"><strong class="text-primary">Ações em massa:</strong></div>
  <div class="panel-body">
      <div class="row">
          <div class="col-lg-6 col-xs-12">
              <div class="row">
                  <div class="col-lg-2 col-xs-3">
                      <a role='button' class='btn btn-xs btn-default alert-select-all'>Marcar todos</a>
                  </div>
                  <div class="col-lg-2 col-xs-3">
                      <a role='button' class='btn btn-xs btn-default alert-noselect-all'>Desmarcar todos</a>
                  </div>
                  <div class="col-lg-2 col-xs-3 col-lg-offset-1 col-xs-offset-1">
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
            <th></th>
        </tr>
    </thead>
    <tbody>
<?php $i=1;?>        
<?php foreach ($alerts as $alert): ?>
        <tr class="data"> 
            <th scope="row"><?=$i?> <?=Html::checkbox($alert->formName().'[][id]', false, ['value'=>$alert->id])?></th> 
            <td data-toggle="tooltip" data-placement="left" title="<?=Yii::$app->formatter->asDate($alert->created_date)?>"><?=Yii::$app->formatter->asRelativeTime($alert->created_date)?></td>
            <td><?=Html::img('images/icons/marker/'.$alert->type->markerIcon,[]);?> <?=$alert->type->description?></td>
            <td><?=$alert->description?></td>
            <td>
                <a data-toggle="tooltip" data-placement="left" title="Editar" role='button' class='btn btn-xs btn-default alert-update'><span class="glyphicon glyphicon-edit"></span></a>
                <a data-toggle="tooltip" data-placement="left" title="Desativar" role='button' class='btn btn-xs btn-default alert-disable-one'><span class="glyphicon glyphicon-remove"></span></a>
                <a data-toggle="tooltip" data-placement="left" title="Ver no mapa" role='button' class='btn btn-xs btn-default alert-view-on-map'><span class="glyphicon glyphicon-globe"></span></a>
                <?php // Html::hiddenInput($alert->formName().'[id]', $alert->id)?>
            </td>
        </tr> 
<?php $i++;?>
<?php endforeach; ?>
    </tbody> 
</table>
</div>