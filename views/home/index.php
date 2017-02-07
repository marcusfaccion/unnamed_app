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
<div>
    <div id="geocoding-pane" class="pane col-lg-4 col-md-5 col-xs-11">
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In mauris nulla, pharetra et ante ut, interdum volutpat nulla. Donec hendrerit massa velit, ac gravida lectus suscipit at. Sed felis libero, finibus sed lorem quis, efficitur faucibus libero. Pellentesque posuere dolor in aliquam molestie. Suspendisse a lorem eget ante consectetur ullamcorper. Pellentesque eros mauris, facilisis nec convallis quis, pharetra a urna. Fusce auctor urna rutrum ex hendrerit lacinia. Donec in suscipit massa. Integer ac quam lobortis, lobortis sem eget, euismod sapien. Etiam eros massa, feugiat a eros et, faucibus fermentum orci. Aliquam erat volutpat. Integer convallis lacus consectetur, aliquam enim non, auctor quam. Integer sed est purus. Proin ullamcorper ornare luctus. Nulla in pretium augue, quis commodo leo. Donec vestibulum magna non sapien convallis, vitae venenatis ante placerat.

    </p><p>Praesent posuere erat at ante tincidunt pellentesque. Aenean id dignissim ligula. Phasellus augue lacus, cursus ac quam ac, congue mollis purus. Duis ornare laoreet aliquet. Etiam elementum mi non lectus imperdiet, vel condimentum sapien tristique. Phasellus ac purus tellus. Fusce eu fermentum velit, ut porta urna.

    </p><p>Maecenas tempus neque nec commodo facilisis. Suspendisse maximus sapien suscipit ligula euismod, et commodo mauris dictum. Suspendisse tempus dictum sagittis. Morbi lectus dui, tempor aliquam congue nec, bibendum ut lectus. Cras accumsan porttitor pellentesque. Proin dictum laoreet dictum. Curabitur vel sodales elit. Morbi luctus justo sapien, porttitor fringilla ante condimentum congue. Pellentesque quis suscipit justo. In aliquet ex sit amet eros viverra placerat.

    </p><p>Donec lacus nulla, dapibus quis condimentum at, eleifend in nibh. Aliquam ligula turpis, dapibus ut justo id, vestibulum convallis tortor. Curabitur placerat, lacus a finibus ornare, massa enim tristique sem, ac auctor tortor dui ac odio. Quisque pretium rhoncus vehicula. Vivamus ornare fringilla erat. Mauris fermentum nisi vitae massa rhoncus aliquam in eu orci. Donec posuere ac justo quis elementum. Sed tempus pulvinar urna, nec interdum massa dictum ac. Sed nibh mi, rhoncus eget nibh iaculis, venenatis pellentesque massa. Maecenas pharetra ullamcorper nibh, placerat hendrerit eros eleifend vel. Maecenas pharetra id nulla eget ullamcorper. Morbi faucibus mi vel tempor varius. Suspendisse id mauris nunc. Nulla ornare eget magna bibendum malesuada. Interdum et malesuada fames ac ante ipsum primis in faucibus. Donec id pharetra lacus, ut egestas sem.

    </p><p>Quisque vulputate lectus quis libero convallis dapibus in in ipsum. Vestibulum quis risus consequat eros tempor finibus. Ut semper interdum orci. Proin lacus ante, scelerisque consectetur fermentum nec, feugiat sit amet mauris. Proin gravida nec metus sed maximus. Cras volutpat odio fermentum, consectetur ex nec, dapibus dui. Aliquam enim ex, blandit et porta vel, placerat vitae ante. Pellentesque tortor turpis, tincidunt sed consectetur sit amet, rhoncus id neque. Cras pharetra at lacus vel eleifend. Nam mollis pretium arcu. Morbi diam justo, vestibulum a magna ac, rutrum pellentesque metus. Praesent et mauris ut libero elementum commodo. Aliquam a ipsum luctus, posuere nunc eget, rutrum justo. Phasellus metus metus, euismod vel auctor a, luctus sit amet arcu. Vestibulum mi dolor, porttitor at lacus eu, pulvinar euismod sapien.
    </p>
    </div>
    <div id='geocoding-pane-toggle' role='button' class="toggle-pane btn">
        <span class="glyphicon glyphicon-chevron-right"></span>
    </div>
</div>


<?php echo $this->render('_modals.php'); ?>