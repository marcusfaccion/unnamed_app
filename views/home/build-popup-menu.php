<ul id='map_menu' class="nav nav-pills nav-stacked">
    <li><a role="button" data-toggle="modal" data-target="#home_actions_modal" onclick="$('#home_actions_trigger').val($(this).find('span').text()+';alerts')" class="btn btn-default"><span>Alertas</span></a></li>
    <li><a role="button" data-toggle="modal" data-target="#home_actions_modal" onclick="$('#home_actions_trigger').val($(this).find('span').text()+';events')" class="btn btn-default"><span>Eventos</span></a></li>
    <li><?php // <a role="button" data-toggle="modal" data-target="#home_actions_modal" class="btn btn-default"><span class="a"> facilidade</span></a> ?>
    <li><a role="button" data-toggle="modal" data-target="#home_actions_modal" onclick="$('#home_actions_trigger').val($(this).find('span').text()+';bike-keepers')" class="btn btn-default"><span>Guardador de bike</span></a></li>
    <li><a role="button" data-toggle="modal" data-target="#home_actions_modal" onclick="$('#home_actions_trigger').val($(this).find('span').text()+';routes')" class="btn btn-default"><span>Modo explorador</span></a></li>
    <?php // <li><a role="button" data-toggle="modal" data-target="#home_actions_modal" onclick="$('#home_actions_trigger').val($(this).find('span').text()+';renting')" class="btn btn-default"><span>Aluguel de bike</span></a></li> ?>
    <?php // <a role="button" data-toggle="modal" data-target="#home_actions_modal" onclick="$('#home_actions_trigger').val($(this).find('span').text()+';lending')" class="btn btn-default"><span>Social</span></a> ?>
</ul>