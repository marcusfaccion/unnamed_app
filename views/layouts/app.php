<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
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
        #map { position:absolute; top:0; bottom:0; width:100%; }
    </style>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
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
            ['label' => 'Home', 'url' => ['/home']],
             /*[
            'label' => 'Dropdown',
            'items' => [
                 ['label' => 'Level 1 - Dropdown A', 'url' => '#'],
                 '<li class="divider"></li>',
                 '<li class="dropdown-header">Dropdown Header</li>',
                 ['label' => 'Level 1 - Dropdown B', 'url' => '#'],
            ],
        ],*/
            ['label' => 'Alertas', 'url' => ['/alerts']],
            ['label' => 'Bicicletários', 'url' => ['/bike-keepers']],
            ['label' => 'Feed', 'url' => ['/feed']],
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post', ['class' => 'navbar-form'])
                . Html::img(Url::to("@web/").Yii::$app->user->identity->avatar, ['class'=>'img-circle wide-px5-8 tall-px5-8'])
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="<?php echo(Yii::$app->controller->id==='home'?'container-fluidMap':'container') ?>">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Marcus Faccion <?php //= date('Y') ?> <a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/"><img alt="Creative Commons License" style="border-width:0" src="images/license/cc-by-sa/v4.png" class="col-xs-offset-0 col-sm-offset-0"></a></p>
       
        <?php /* <p class="pull-right"><?= Yii::powered() ?></p> */?>
    </div>
</footer>
<?php if(Yii::$app->user->isGuest) {echo $this->renderFile('@app/views/site/_modal_login.part.php');} ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
