<?php

/* @var $this yii\web\View */

$this->title = 'Rastreador de ecomenda em tempo real';
?>
<div class="site-index">

    <div class="jumbotron col-sm-offset-2 col-sm-8">
        <?php //<h1> Código da Encomenda:</h1>?>
        <form class="form-inline" name="track-form" id="index-track-form" method="post">
          <div class="form-group <?php //has-success?>">
            <label class="sr-only" for="Encomenda_name">Código da Encomenda</label>
            <div class="input-group">
              <div class="input-group-addon">#</div>
              <input type="text" class="form-control input-lg" name="Encomenda[name]" id="Encomenda_name" placeholder="Código da Encomenda">
              <div class="input-group-addon"><a href="#" onclick="$('#index-track-form').submit();" title="Track It!"><span class="glyphicon glyphicon-eye-open"<?php //glyphicon-search ?> aria-hidden="true"></span>
</a></div>
            </div>
          </div>
        </form>
      
        <cite class="text-muted">Veja informações e a localização em tempo real de sua encomenda.</cite>
        
        <?php  /*<div class="col-sm-12">&nbsp;</div>
         <p><a class="btn btn-lg btn-success" href="#" onclick="">Rastrear!</a></p>*/?>
    </div>
<?php /*
    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div>
        </div>

    </div>*/?>
</div>
