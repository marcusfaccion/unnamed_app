<?php
use yii\jui\Slider;
use yii\bootstrap\Html;
?>
<div class="text-center bottom-buffer-3 bike-keeper-used-capacity-display">
    <span role='buttom' class="btn btn-down glyphicon glyphicon-menu-down tsize-5 right-buffer-1"></span><strong class="tsize-7 text-danger border-radius-5" id="bike_keeper_<?=$bike_keeper->id?>_used_capacity_display"><?=$bike_keeper->used_capacity==null?0:$bike_keeper->used_capacity?></strong><span role='buttom' class="btn btn-up glyphicon glyphicon-menu-up tsize-5 left-buffer-1"></span>
</div>
<?=Slider::widget([
    'id'=>"bike_keeper_{$bike_keeper->id}_used_capacity_slider",
    'clientOptions' => [
        'min' => 0,
        'max' => $bike_keeper->capacity,
        'value' => $bike_keeper->used_capacity,
        'animate'=>true
    ],
    'clientEvents' => [
        'slide'=> new \yii\web\JsExpression("
                    function( event, ui ) {
                        $('#bike_keeper_{$bike_keeper->id}_used_capacity_display').html(ui.value);
                        app.bike_keeper.used_capacity = ui.value;
                    }"),
        ],
    'options'=>['class'=>'bottom-buffer-5 bike-keeper-slider'],
])?>

<div class="text-center">
  <?=Html::button('Salvar',['class'=>'btn btn-sm btn-success bike-keeper-capacity save'])?>
  <?=Html::button('Cancelar',['class'=>'btn btn-sm btn-danger', 'data-dismiss'=>'modal'])?>
  <?=Html::hiddenInput($bike_keeper->formName().'[id]',$bike_keeper->id, ['id'=>'bike-keeper-id'])?>
  <?=Html::hiddenInput($bike_keeper->formName().'[used_capacity]',$bike_keeper->used_capacity, ['id'=>'bike-keeper-used-capacity'])?>
</div>