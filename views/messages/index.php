<?php
/* @var $this yii\web\View */
use app\assets\AppMessagesAsset;
use yii\helpers\Html;
?>
<?php
AppMessagesAsset::register($this);
$this->title = 'Bike Social - Mensagens';
?>

<div class="row height-100">
    <div id='messages-user-friends-sidebar' class="col-lg-4 col-md-4 col-xs-12 height-100 overflow-y">
        <div class="row">
            <div class="col-lg-11 col-md-11 col-xs-10">
                <h4 class="text-muted strong-6 text-center">Amigos <span class="badge"><?=(count($user->friends)>0)?count($user->friends):null?></span></h4>
            </div>
            <div class="col-lg-1 col-md-1 col-xs-1"><span class="strong-8 glyphicon glyphicon-chevron-left btn collapse-sidebar left-pbuffer-0 text-muted tsize-6"></span></div>
        </div>
        <div class="row">
            <div class="col-lg-11 col-md-10 col-xs-10">
                <?=Yii::$app->controller->renderPartial('_friends_sidebar',['user'=>$user]);?>
            </div>
            <?php // <div class="col-lg-1 col-md-1 col-xs-1">>></div> ?>
        </div>
    </div>
    <div id='messages-conversation' class="col-lg-8 col-md-8 col-xs-12 height-100 bg-light-default border-left-1">
        <div class="row tall-11 overflow-y">
            <div class="col-lg-11 col-md-10 col-xs-12">
                <div id='messages-conversation-placeholder' class="jumbotron"> 
                    <h2 class="text-muted"><cite>Selecione uma conversa.</cite></h2>
                </div>
            </div>
        </div>
        <div id='conversations-text-input' style="position: relative; top: -2%">
            <div class="input-group">
              <?=Html::textarea((new app\models\UserConversations)->formName().'[text]', '',['class'=>'wide-9 tall-px5-10 border-radius-3 form-control', 'id'=>  strtolower((new app\models\UserConversations)->formName()).'-text', 'placeholder'=>'Escreva sua mensagem aqui.'])?>
              <div class="input-group-addon"><span class="glyphicon glyphicon-ok text-success tsize-4 strong-6 btn btn-sm send"></span></div>
            </div>
        </div>
    </div>
</div>
<div id='messages-show-sidebar' class='btn' data-toggle='tooltip' title="Painel de amigos" style="display:none">
</div>