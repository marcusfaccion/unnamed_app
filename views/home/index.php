<?php
use yii\bootstrap\Html;
use app\assets\AppHomeAsset;
use app\assets\AppAlertsAsset;
use app\assets\AppBikeKeepersAsset;
use app\models\Users;
/* @var $this yii\web\View */
$this->title = 'Apicação Colaborativa para Ciclistas';
?>

<?php
AppHomeAsset::register($this);
AppAlertsAsset::register($this);
AppBikeKeepersAsset::register($this);
?>

<div id='map'>
    <div class="app-horizontal-widget">   
        <div class="gb_hb gb_wf gb_R gb_vf gb_fa">
            <div class="gb_hc gb_wf gb_R">
                <div class="gb_ga" guidedhelpid="gbifp" id="gbsfw">
                    <a id='home_btn_friends' role="button" onclick="$('#home_actions_trigger').val($(this).find('span').text()+';friends')" class="btn btn-default home"><span>Amigos</span><span class="badge bg-primary col-md-offset-1"><?=Users::findOne(Yii::$app->user->id)->friends->total?><span/></a>
                    <a id='home_btn_my_location' role="button" class="btn btn-default home"><span>Minha localização</span></a>
                    <?php // <a role="button" onclick="$('#home_actions_trigger').val($(this).find('span').text()+';layers')" class="btn btn-default"><span>Filtros</span></a>?>
                    <?php echo Html::hiddenInput("home_actions_trigger", 'Alertas;alerts', ['id'=>'home_actions_trigger']);?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->render('_modals.php'); ?>