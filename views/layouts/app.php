<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
//use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
     <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
    
    <style>
        body { margin:0; padding:0; }
        <?=Yii::$app->controller->renderFile(in_array(Yii::$app->controller->id, Yii::$app->params['app.layout.mapOverlay.controllers'])?'@app/web/css/_map-overlay.css':'@app/web/css/_map.css')?>
        <?php if(in_array(Yii::$app->controller->id, Yii::$app->params['app.layout.noMap.controllers']))echo Yii::$app->controller->renderFile('@app/web/css/_nomap.css')?>
    </style>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php 
/**
 *  Dados para o JavaScript
 */
echo Html::hiddenInput('App[user_id]',Yii::$app->user->isGuest?null:Yii::$app->user->identity->id, ['id'=>'app-user-id']);
echo Html::hiddenInput('App[controller_id]',Yii::$app->user->isGuest?null:Yii::$app->controller->id, ['id'=>'app-controller-id']);
?>
    
<?php $this->beginBody() ?>
<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 
        "<ul class='list-unstyled list-inline'>
            <li>"
            .Html::img(Url::to(['images/icons/logo_48.png']))
            ."</li>
            <li>
            <span class='text-white'><strong>".Yii::$app->name.'</strong></span>
            </li>
        </ul>',
        'brandOptions' => ['class'=>'no-paddings'],
        'brandUrl' => ['/home'],
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Início', 'url' => ['/home'], 'active'=>(Yii::$app->controller->id=='home')],
             /*[
            'label' => 'Dropdown',
            'items' => [
                 ['label' => 'Level 1 - Dropdown A', 'url' => '#'],
                 '<li class="divider"></li>',
                 '<li class="dropdown-header">Dropdown Header</li>',
                 ['label' => 'Level 1 - Dropdown B', 'url' => '#'],
            ],
        ],*/
            ['label' => 'Alertas', 'url' => ['/alerts'], 'active'=>(Yii::$app->controller->id=='alerts')],
            ['label' => 'Bicicletários', 'url' => ['/bike-keepers'], 'active'=>(Yii::$app->controller->id=='bike-keepers')],
            ['label' => 'Mensagens', 'url' => ['/messages'], 'active'=>(Yii::$app->controller->id=='messages')],
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post', ['class' => 'navbar-form'])
                . Html::img(Url::to("@web/").Yii::$app->user->identity->avatar, ['class'=>'img-circle wide-px5-8 tall-px5-8'])
                . Html::submitButton(
                    'Logout',
                    ['class' => 'btn btn-link']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>
    <div <?=(in_array(Yii::$app->controller->id, ['messages'])?"style='height: 100%;position: absolute;left: 0;right: 0;'":'')?> class="<?php echo(in_array(Yii::$app->controller->id, Yii::$app->params['app.layout.mapOverlay.controllers'])?'container-fluidMap':'container-fluid') ?>">
        <?php //echo Breadcrumbs::widget([ 'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [], ]) ?>
        <?= $content ?>
    </div>
</div>
<?php if(!in_array(Yii::$app->controller->id, ['messages'])):?>
<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Marcus Faccion <?php //= date('Y') ?> <a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/"><img alt="Creative Commons License" style="border-width:0" src="images/license/cc-by-sa/v4.png" class="col-xs-offset-0 col-sm-offset-0"></a></p>
       
        <?php /* <p class="pull-right"><?= Yii::powered() ?></p> */?>
    </div>
</footer>
<?php endif;?>
<?php if(Yii::$app->user->isGuest) {echo $this->renderFile('@app/views/site/_modal_login.php');} ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
