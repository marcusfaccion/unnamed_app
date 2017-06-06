<?php
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = Yii::$app->name. ' - Site';
?>
<div class="site-index">
    
     <div id="site_showcase" class="jumbotron home text-hint strong-6">
         <h1 class='strong-jumbotron-6'>Pedalar é saúde <span class='text-jumbotron-danger glyphicon glyphicon-heart'></span> compartilhe essa experiência com seus amigos!</h1>
            <div class="row top-buffer-9">
                <div class="col-md-3">
                    <img class="img-responsive" src="<?=Url::to(['images/icons/site_showcase_bike1_256.png'])?>">
                </div>
                <div class="col-md-8">
                    <ul class="tsize-6 text-left text-hint">
                        <li class="top-buffer-4">
                            Comparthile alertas
                        </li>
  						<li class="top-buffer-4">
                            Comparthile bicicletários
                        </li>
                        <li class="top-buffer-4">
                            Ache onde guardar sua bike
                        </li>
                        <li class="top-buffer-4">
                            Envie mensagens para seus amigos
                        </li>
  						<li class="top-buffer-4">
                            Acompanhe as interações de seus amigos
                        </li>
                    </ul>
                </div>
            </div>
    </div>
    <div id="login-form-container" class="container">
        <a name="site-signup-form" href="#site-signup-form"></a><h3 class="col-md-offset-1 text-hint"> Crie sua conta:</h3>
            <div class="row top-buffer-5">
                <div class="col-md-12">
                   <?php
                        if(Yii::$app->user->isGuest){
                            echo $this->renderFile('@app/views/site/_form_signup.php', ['user'=>$user, 'form_number' => 2, 'route'=>Url::to(['/site'])]);
                        }
                    ?>
                </div>
            </div>
    </div>
   
</div>
<?=Yii::$app->controller->renderPartial('_modals');?>