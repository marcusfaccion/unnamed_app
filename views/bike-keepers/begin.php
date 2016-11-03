<?php 

/**
 * @var BikeKeepers $bike_keeper
 */

use marcusfaccion\helpers\String;
use app\assets\AppBikeKeepersAsset;
?>

    <div class="bike-keepers-widget-index">
       <div id='bike-keepers-widget-menu' class="row col-xs-offset-2">
           
       </div>
       <div id="home_actions_bike-keepers_form" class="row col-xs-offset-1 bottom-buffer-5">
          <?php echo $this->renderAjax('_bike-keeper-form', ['bike_keeper'=>$bike_keeper]); ?>
       </div>
    </div>