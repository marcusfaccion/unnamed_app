<?php
use yii\bootstrap\Html;
?>
 
<?php if(count($bike_keepers)>0):?>
 <div class="col-lg-12 col-xs-12 panel panel-default top-buffer-2">
  <!-- Default panel contents -->
  <div class="panel-heading"><strong class="text-primary">Ações em massa:</strong></div>
  <div class="panel-body">
      <div class="row">
          <div class="col-lg-6 col-xs-12 left-pbuffer-0 right-pbuffer-0">
              <div class="row">
                  <div class="col-lg-2 col-xs-4 right-pbuffer-0">
                      <a role='button' class='btn btn-xs btn-default bike-keeper-select-all'>Marcar todos</a>
                  </div>
                  <div class="col-lg-2 col-xs-4 left-pbuffer-0 right-pbuffer-0">
                      <a role='button' class='btn btn-xs btn-default bike-keeper-noselect-all'>Desmarcar todos</a>
                  </div>
                  <div class="col-lg-2 col-xs-3 left-pbuffer-0 right-pbuffer-0 left-buffer-2">
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
            <th>Tipo</th>
            <th>Endereço</th>
            <th>Descrição</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php $i=1;?>        
    <?php foreach ($bike_keepers as $bike_keeper): ?>
        <tr class="data"> 
            <th scope="row"><?=Html::checkbox($bike_keeper->formName().'[][id]', false, ['value'=>$bike_keeper->id])?> <?=$i?></th> 
            <?php if($bike_keeper->public):?>
            <td data-toggle="tooltip" data-placement="right" title="Bicicletário de uso público e gratuíto"><span class="text-success"><strong>Público</strong></span></td>
            <?php else:?>
            <td data-toggle="tooltip" data-placement="right" title="Bicicletário de uso particular com cobrança de diária" class="text-danger"><span class="text-danger glyphicon glyphicon-usd"></span><strong>  Privado</strong> </td>
            <?php endif;?>
            <td class="text-muted"><strong><?=$bike_keeper->address?></strong></td>
            <td class="text-muted"><strong><?=$bike_keeper->description?></strong></td>
            <td>
                <?php if(!$bike_keeper->public):?>
                <span data-toggle="tooltip" data-placement="left" title="Ajustar a capacidade de vagas" >
                    <a data-toggle="modal" data-target="#bike_keepers_used_capacity_modal" role='button' class='btn btn-xs btn-default bike-keeper-capacity'><span class="glyphicon glyphicon-adjust"></span></a>                
                </span>
                <?php endif;?>
                <span data-toggle="tooltip" data-placement="left" title="Editar informações" >
                    <a data-toggle="modal" data-target="#bike_keepers_update_modal" role='button' class='btn btn-xs btn-default bike-keeper-update'><span class="glyphicon glyphicon-edit"></span></a>                
                </span>
                <a data-toggle="tooltip" data-placement="left" title="Cuidado: Desativar o bicicletário!" role='button' class='btn btn-xs btn-default btn-danger bike-keeper-disable-one'><span class="text-white glyphicon glyphicon-trash"></span></a>
                <a data-toggle="tooltip" data-placement="left" title="Ver no mapa" role='button' class='btn btn-xs btn-default bike-keeper-view-on-map'><span class="text-primary glyphicon glyphicon-globe"></span></a>
                <?php if(!$bike_keeper->public):?>
                    <?php if($bike_keeper->isItOpen):?>
                        <a data-toggle="tooltip" data-placement="left" title="Fechar o bicicletário por hoje" role='button' class='btn btn-xs btn-default bg-light-danger bike-keeper-off'><span class="text-danger glyphicon glyphicon-off"></span></a>
                    <?php endif;?>
                    <?php if(!$bike_keeper->isItOpen):?>
                        <a data-toggle="tooltip" data-placement="left" title="Abrir bicicletário ao uso agora" role='button' class='btn btn-xs btn-default bg-light-green bike-keeper-on'><span class="text-success glyphicon glyphicon-off"></span></a>
                    <?php endif;?>
                <?php endif;?>
                <?php // Html::hiddenInput($bike-keeper->formName().'[id]', $bike-keeper->id)?>
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
                    <strong>Não há bicicletários cadastrados por enquanto.</strong></div>
            
</div>
<?php endif; ?>
