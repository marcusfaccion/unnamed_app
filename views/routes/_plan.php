<div id='user-navigation-plan' class="row">
    <?php $i=0;?>
    <?php foreach($routes as $route):?>
    <div class="col-lg-12 col-xs-12 right-pbuffer-2 left-pbuffer-2">
        <ul class="list-unstyled">
            <li>
                <h3 class='text-white bg-primary text-center border-radius-3'><strong>Percurso <span class="glyphicon glyphicon-list-alt"></span></strong></h3>
                <ul class="list-unstyled left-pbuffer-4 tsize-5">                    
                    <li class="top-buffer-4 text-muted"><strong><span class='glyphicon glyphicon-ok'></span> Distância: <?=Yii::$app->formatter->format(($route->distance/1000), 'decimal').' km'?></strong></li>    
                    <li class="top-buffer-4 text-muted"><strong><span class='glyphicon glyphicon-ok'></span> Duração: <?=Yii::$app->formatter->asDuration($route->duration, 1)?></strong></li>    
                </ul>                
            </li>
            <li>
                <h3 class='text-white bg-primary text-center border-radius-3'><strong>Alertas <span class="glyphicon glyphicon-bullhorn"></span></strong></h3>
                <ul class="list-unstyled left-pbuffer-4 tsize-5">
                <?php if(count($alerts[$i])>0):?>
                    <?php if(isset($_alerts[$i]['interdicoes'])):?>
                    <li class="top-buffer-4 text-muted"><strong><span class='glyphicon glyphicon-ok'></span> Interdição de via: <?=count($_alerts[$i]['interdicoes'])?></strong></li>    
                    <?php endif;?>

                    <?php if(isset($_alerts[$i]['roubos_e_furtos'])):?>
                    <li class="top-buffer-4 text-muted"><strong><span class='glyphicon glyphicon-ok'></span> Roubos de ciclistas: <?=count($_alerts[$i]['roubos_e_furtos'])?></strong></li>    
                    <?php endif;?>

                    <?php if(isset($_alerts[$i]['perigo_na_via'])):?>
                    <li class="top-buffer-4 text-muted"><strong><span class='glyphicon glyphicon-ok'></span> Perigo na via: <?=count($_alerts[$i]['perigo_na_via'])?></strong></li>    
                    <?php endif;?>

                    <?php if(isset($_alerts[$i]['generico'])):?>
                    <li class="top-buffer-4 text-muted"><strong><span class='glyphicon glyphicon-ok'></span> Genéricos: <?=count($_alerts[$i]['generico'])?></strong></li>    
                    <?php endif;?>
                    <li class="top-buffer-5 text-muted"><strong>Total: <?=count($alerts[$i])?></strong></li>    

                 <?php else:?>
                    <li class="text-muted top-buffer-4"><strong> Pista livre, nenhum alerta por onde você vai passar! <span class="glyphicon glyphicon-thumbs-up tsize-6"></span></strong></li>
                 <?php endif;?>
                </ul>                
            </li>
        </ul>
    </div>
    <?php ++$i;?>
    <?php endforeach;?>
    <div class="col-lg-12 col-xs-12 right-pbuffer-2 left-pbuffer-2">
        <h3 class='text-white bg-primary text-center border-radius-3'><strong>Bicicletários <span class="glyphicon glyphicon-home"></span></strong></h3>
        <ul class="list-unstyled left-pbuffer-4 tsize-5">
        <?php if(count($bike_keepers)>0):?>
            <li class="top-buffer-4 text-muted tsize-5"><strong><?=Yii::t('app','{n, plural, =1{Existe} other{Existem}}', ['n'=>count($bike_keepers)])?> <?=count($bike_keepers)?> <?=Yii::t('app','{n, plural, =1{bicicletário} other{bicicletários}}', ['n'=>count($bike_keepers)])?> num raio de 1km do destino</strong></li>    
            <?php if(isset($_bike_keepers['public'])):?>
                <li class="top-buffer-4 text-muted"><strong> Públicos : <?=count($_bike_keepers['public'])?></strong></li>    
            <?php endif;?>

            <?php if(isset($_bike_keepers['nonpublic'])):?>
                <li class="top-buffer-4 text-muted"><strong> Privados: <?=count($_bike_keepers['nonpublic'])?></strong></li>    
            <?php endif;?>
         <?php else:?>
            <li class="text-muted top-buffer-4"><strong> Nenhum bicicletário cadastrado num raio de 1km do seu destino <span class="glyphicon glyphicon-thumbs-down tsize-6"></span></strong></li>
         <?php endif;?>
        </ul>                
    </div>

</div>