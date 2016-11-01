<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
//use yii\bootstrap\Button;
//use yii\widgets\Breadcrumbs;
use app\assets\SiteAsset;

SiteAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Logo aqui',
        'brandUrl' => Yii::$app->user->isGuest?Url::to(['/'.Yii::$app->defaultRoute]):Url::to(['/'.Yii::$app->homeUrl]),
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => true, 
        'items' => [
          //  ['label' => 'Home', 'url' => ['/site/index']],
          //  ['label' => 'About', 'url' => ['/site/about']],
          //  ['label' => 'Contact', 'url' => ['/site/contact']],
               Yii::$app->user->isGuest ? (
                 ['label' => 'Entrar',
               //'options' => ['class' => 'TesteZ'],
               'linkOptions' => [   
                                    'class' => 'btn btn-danger',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#modal_login',
                                ],
                ]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post', ['class' => 'navbar-form'])
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
     //   Button::begin();
       //     Button::widget();
        //Button::end();
    NavBar::end();
    ?>

    <div class="container">
        <?php /*Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) */ ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Marcus Faccion <?php //= date('Y') ?> <a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/"><img alt="Creative Commons License" style="border-width:0" src="<?=Url::to("@web/images/license/cc-by-sa/v4.png")?>" class="col-xs-offset-0 col-sm-offset-0"></a></p>
       
       <?php /* <p class="pull-right"><?= Yii::powered() ?></p> */?>
    </div>
</footer>
<?php if(Yii::$app->user->isGuest) {echo $this->renderFile('@app/views/site/_modal_login.php', ['form_number' => 1]);} ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
