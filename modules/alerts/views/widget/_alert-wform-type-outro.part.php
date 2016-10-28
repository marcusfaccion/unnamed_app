<?php
use yii\bootstrap\Html;
use yii\widgets\ActiveForm;
use app\assets\AppAlertsAsset;
//Html::radioList('', $selection, $items)
?>
<h1>Outro</h1>

<label class="radio-inline">
  <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1"> 1
</label>
<label class="radio-inline">
  <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> 2
</label>
<label class="radio-inline">
  <input type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3"> 3
</label>
<div class="top-buffer-4 text-center">
    <?php echo Html::button('Voltar', ['class'=>'btn btn-danger back']);?>
    <?php echo Html::button('Salvar', ['class'=>'btn btn-success']);?>
</div>
