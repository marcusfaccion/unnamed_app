<?php
use app\models\UserSharings;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\UserFeedings;
use yii\widgets\ActiveForm;
use app\models\UserNavigationRoutes;
?>
<div class="users-default-index">
<p class="text-hint strong-6 tsize-4">Deixe um comentário sobre a rota para seus amigos:</p>
    
        <?php
            $user_feeding = (isset($user_feeding) && $user_feeding instanceof UserFeedings)?$user_feeding: new UserFeedings(['scenario'=>UserFeedings::SCENARIO_CREATE]);
            $user_sharing = (isset($user_sharing) && $user_sharing instanceof UserSharings)?$user_sharing: new UserSharings(['scenario'=>  UserSharings::SCENARIO_CREATE]);
            $user_route = (isset($user_route) && $user_sharing instanceof UserNavigationRoutes)?$user_route: new UserNavigationRoutes(['scenario'=> UserNavigationRoutes::SCENARIO_CREATE]);
            
            $form = ActiveForm::begin([
                'id' =>'user-sharings-form',
                'options' => ['data' => ['pjax' => true], 'enctype' => 'multipart/form-data', 'id'=>'user-sharings-form'],
                'method' => 'post',
                'action' => Url::to('user-sharings/create'),
                'fieldConfig' => [
                    'template' => "<div class='row top-buffer-1'><div class=\"col-lg-10 col-xs-11\">{input}</div>\n<div class=\"col-lg-2 col-xs-8\">{error}</div></div>",
                    'labelOptions' => ['class' => ''],
                ],
            ]);
        ?>

        <?php // Model UserSharings ?>
        <?=Html::hiddenInput($user_sharing->formName().'[user_id]', $user_sharing->user_id, ['id'=>  strtolower($user_sharing->formName()).'-user-id']);?>
        <?=Html::hiddenInput($user_sharing->formName().'[sharing_type_id]', $user_sharing->user_id, ['id'=>  strtolower($user_sharing->formName()).'-sharing-type-id']);?> 
        <?=Html::hiddenInput($user_sharing->formName().'[content_id]', $user_sharing->content_id, ['id'=>  strtolower($user_sharing->formName()).'-content-id']);?> 
       
        <?php // Model UserFeedings ?>
        <?=$form->field($user_feeding, 'text', [])->textArea(['class' => 'feeding-text wide-12 form-control']);?>
        
        <?php // Model UserNavigationRoutes ?>
        <?=Html::hiddenInput($user_route->formName().'[origin_geom]', $user_route->origin_geojson, ['id'=>  strtolower($user_route->formName()).'-origin-geom']);?>
        <?=Html::hiddenInput($user_route->formName().'[destination_geom]', $user_route->origin_geojson, ['id'=>  strtolower($user_route->formName()).'-destination-geom']);?>
        <?=Html::hiddenInput($user_route->formName().'[line_string_geom]', $user_route->line_string_geom, ['id'=>  strtolower($user_route->formName()).'-line-string-geom']);?>
        <?=Html::hiddenInput($user_route->formName().'[duration]', $user_route->duration, ['id'=>  strtolower($user_route->formName()).'-duration']);?>
        
        

        <?php
        $form->end();
        ?>
</div>
