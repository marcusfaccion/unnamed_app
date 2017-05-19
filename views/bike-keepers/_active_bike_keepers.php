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
                      <a role='button' class='btn btn-xs btn-default bike-keeper-select-all'>Marcar todos</a>
                  </div>
                  <div class="col-lg-2 col-xs-3">
                      <a role='button' class='btn btn-xs btn-default bike-keeper-noselect-all'>Desmarcar todos</a>
                  </div>
                  <div class="col-lg-2 col-xs-3 col-lg-offset-1 col-xs-offset-1">
                      <?=Html::button('<span class="glyphicon glyphicon-remove-circle"></span> Desativar', ['class'=>'btn btn-xs btn-danger bike-keeper-disable-all'])?>
                  </div>
              </div>
        </div>
      </div>
  </div>
 </div>

<div class="top-buffer-2">
      <table id='bike-keepers-table' class="table table-hover table-responsive"> 
    <thead> 
        <tr> 
            <th>#</th>
            <th>Data de envio</th>
            <th>Descrição</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
<?php $i=1;?>        
<?php foreach ($bike_keepers as $bike_keeper): ?>
        <tr class="data"> 
            <th scope="row"><?=$i?> <?=Html::checkbox($bike_keeper->formName().'[][id]', false, ['value'=>$bike_keeper->id])?></th> 
            <td data-toggle="tooltip" data-placement="left" title="<?=Yii::$app->formatter->asDate($bike_keeper->created_date)?>"><?=Yii::$app->formatter->asRelativeTime($bike_keeper->created_date)?></td>
            <td><?=$bike_keeper->description?></td>
            <td>
                <a data-toggle="tooltip" data-placement="left" title="Editar" role='button' class='btn btn-xs btn-default bike-keeper-update'><span class="glyphicon glyphicon-edit"></span></a>
                <a data-toggle="tooltip" data-placement="left" title="Desativar" role='button' class='btn btn-xs btn-default bike-keeper-disable-one'><span class="text-danger glyphicon glyphicon-remove-circle"></span></a>
                <a data-toggle="tooltip" data-placement="left" title="Ver no mapa" role='button' class='btn btn-xs btn-default bike-keeper-view-on-map'><span class="text-primary glyphicon glyphicon-globe"></span></a>
                <?php // Html::hiddenInput($bike-keeper->formName().'[id]', $bike-keeper->id)?>
            </td>
        </tr> 
<?php $i++;?>
<?php endforeach; ?>
    </tbody> 
</table>
</div>