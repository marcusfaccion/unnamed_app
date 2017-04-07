<?php
use yii\bootstrap\Html;
use app\assets\AppHomeAsset;
use app\assets\AppAlertsAsset;
use app\assets\AppBikeKeepersAsset;
use app\assets\AppFriendsAsset;
use app\models\Users;
/* @var $this yii\web\View */
$this->title = 'Apicação Colaborativa para Ciclistas';
?>

<?php
AppHomeAsset::register($this);
AppAlertsAsset::register($this);
AppBikeKeepersAsset::register($this);
AppFriendsAsset::register($this);
?>

<div id='map'>
    <div class="app-horizontal-widget over-map">   
        <div>
            <div>
                <div>
                    <a id='home_btn_friends' role="button" data-toggle="modal" data-target="#home_actions_modal" onclick="$('#home_actions_trigger').val($(this).find('span').text()+';friends')" class="btn btn-default home"><span>Amigos </span><small><span class="glyphicon glyphicon-user"></span></small></a>
                    <a id='home_btn_my_location' role="button" title='Ativar localização' class="btn btn-default home"><span class='glyphicon glyphicon-screenshot'></span></a>
                    <?php // <a role="button" onclick="$('#home_actions_trigger').val($(this).find('span').text()+';layers')" class="btn btn-default"><span>Filtros</span></a>?>
                    <?php echo Html::hiddenInput("home_actions_trigger", 'Alertas;alerts', ['id'=>'home_actions_trigger']);?>
                </div>
            </div>
        </div>
    </div>
</div>

<div id='user-navigation-container' class='pane col-lg-4 col-md-5 col-xs-11'>
    <div class='row'>
        <div id="user-navigation-pane" class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
            <div class="row">
                <div id="user-navigation-header" class="col-lg-12">
                    <?php /* <ul class="list-unstyled">
                      <li>
                        <input type="text" autocomplete="true" name="nav-panel-i-o" placeholder="Origem">
                      </li>
                     <li>
                        <input type="text" autocomplete="true" name="nav-panel-i-d" placeholder="Destino">
                     </li>
                     <li>
                        <button class="btn btn-sm btn-default">Pedalar!!!</button>
                     </li>
                    </ul>*/?>
                    <div id='errors'></div>
                    <div id='inputs'></div>
                </div>
              <div id="user-navigation-details" class="col-lg-12">
                <?php /* <p>latin lorem psuin
                </p><p>latin lorem psuin
                </p><p>latin lorem psuin</p> */ ?>
                 <div id='directions'>
                      <div id='routes'></div>
                      <div id='instructions'></div>
                 </div> 
              </div>
            </div>
        </div>
        <div id='user-navigation-pane-toggle' role='button' class="col-lg-1 col-md-1 col-sm-1 col-xs-1 btn">
                <span class="glyphicon glyphicon-chevron-right"></span>
        </div>
    </div>    
</div>
<div id='home-user-menu-navigation' class='btn'>
</div>

<?php echo $this->render('_modals.php'); ?>