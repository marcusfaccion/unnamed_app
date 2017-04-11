<?php
/* @var $this yii\web\View */
/* @var $user app\models\User */
?>

<?php 

use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\Html;
use app\models\Users;
?>

  <div class="row">
      <div class="col-xs-12 col-md-12 col-lg-12 top-buffer-2">
          <div class="form-group">
            <label class="sr-only" for="user-friend-search-text">Buscar amigos</label>
            <div class="input-group wide-12">
                <?php
                  echo AutoComplete::widget([
                        'id' => 'ac-user-friend',
                        'name' => 'ac-user-friend',
                        'clientOptions' => [
                            'source' => new JsExpression('
                                        function (request, response) {
                                            $.ajax({
                                                 url: "friends/friends-search",
                                                 type: "GET",
                                                 data: request,
                                                 success: function (data) {
                                                     response($.map(JSON.parse(data), function (desc, val) {
                                                         return {
                                                             label: desc.label,
                                                             value: val.value
                                                         };
                                                      }));}
                                            });
                                        }
                                    '),
                            'select' => new JsExpression('
                                        function(){
                                            $.ajax({
                                                 url: "friends/get-friends",
                                                 type: "POST",
                                                 data: {Users : {full_name: $(\'#ac-user-friend\').val()}},
                                                 success: function (data) {
                                                    $(\'#friends-message-panel\').fadeOut(\'now\', function(){$(this).html(\'\')});
                                                    $(\'#friends-subscribe-panel\').html(data);
                                                 }
                                            });
                                        }
                                    '),
//                                'change' => new JsExpression('
//                                            function(){
//                                                $.ajax({
//                                                     url: "friends/get-friends",
//                                                     type: "POST",
//                                                     data: {Users : {full_name: $(\'#ac-user-friend\').val()}},
//                                                     success: function (data) {
//                                                        $(\'#friends-subscribe-panel\').html(data);
//                                                     }
//                                                });
//                                            }
//                                        '),
                            'minLength'=> 2,
                            //'delay'=> 100*0.5,
                        ],
                      'options' => ['placeholder'=>'Buscar amigos...', 'class'=>'wide-12 form-control', 'onKeyPress'=>'onFriendSearchKeyPress(event)']
                    ]);
              ?>
              <div id='friends-search-btn' class="friends input-group-addon btn" role="button" ><span class="glyphicon glyphicon-search"></span></div>
            </div>
          </div>
      </div>
      <?php // <button type="submit" class="btn btn-primary">Pesquisar</button>?>
  </div>
<div class="row">
      <div class="col-xs-12 col-md-12 col-lg-12 top-buffer-2">
          <div id='friends-message-panel'>
              
          </div>
      </div>
</div>    
<div class="row">
    <div id='friends-subscribe-panel' class="col-xs-12 col-md-12 col-lg-12 top-buffer-2">
        
    </div>
    <?php echo Html::hiddenInput((new Users)->formName().'[user_id]', '',['id'=>'user-id']);?>
</div>
