<ul id='map_menu' class="nav nav-pills nav-stacked">
    <li><a role="button" data-toggle="modal" data-target="#home_actions_modal" onclick="$('#home_actions_trigger').val($(this).find('span').text()+';alerts');map.closePopup(map_popup_menu);" class="btn btn-default"><strong><span class="text-muted tsize-4 glyphicon glyphicon-bullhorn"></span> Criar alerta aqui</strong></a></li>
    <?php // <li><a role="button" data-toggle="modal" data-target="#home_actions_modal" onclick="$('#home_actions_trigger').val($(this).find('span').text()+';events')" class="btn btn-default"><span>Eventos</span></a></li>?>
    
    <li><?php // <a role="button" data-toggle="modal" data-target="#home_actions_modal" class="btn btn-default"><span class="a"> facilidade</span></a> ?>
  
    <li><a role="button" data-toggle="modal" data-target="#home_actions_modal" onclick="$('#home_actions_trigger').val($(this).find('span').text()+';bike-keepers');map.closePopup(map_popup_menu)" class="btn btn-default"><strong><span class="text-muted tsize-4 glyphicon glyphicon-home"></span> Criar bicicletário aqui</strong></a></li>
    
    <li class="nav-disabled" style="display:none"><a role="button" onclick="userNavigationStart(true, this);" class="btn btn-default"><strong><span class="text-muted tsize-4 glyphicon glyphicon-screenshot"></span> Ativar navegação</strong></a></li>
    
    <li class="nav-enabled"><a role="button" onclick="userNavigationStart(false, this);" class="btn btn-default"><strong><span class="text-muted tsize-4 glyphicon glyphicon-screenshot"></span> Desativar navegação</strong></a></li>
    
    <li class="nav-enabled"><a role="button" onclick="setOrigin(me.latLng);map.closePopup(map_popup_menu);" class="btn btn-default"><strong><span class="text-muted tsize-4 glyphicon glyphicon-map-marker"></span> Meu local como origem</strong></a></li>
    
    <li class="nav-origin"><a role="button" onclick="setOrigin(app.latLng);map.closePopup(map_popup_menu);" class="btn btn-default"><strong><span class="text-muted tsize-4 glyphicon glyphicon-font"></span> Definir origem</strong></a></li>
    
    <li class="nav-destination"><a role="button" onclick="setDestination(app.latLng);map.closePopup(map_popup_menu);" class="btn btn-default"><strong><span class="text-muted tsize-4 glyphicon glyphicon-bold"></span> Definir destino</strong></a></li>
    <?php // <li><a role="button" data-toggle="modal" data-target="#home_actions_modal" onclick="$('#home_actions_trigger').val($(this).find('span').text()+';renting')" class="btn btn-default"><span>Aluguel de bike</span></a></li> ?>
    <?php // <a role="button" data-toggle="modal" data-target="#home_actions_modal" onclick="$('#home_actions_trigger').val($(this).find('span').text()+';lending')" class="btn btn-default"><span>Social</span></a> ?>
</ul>