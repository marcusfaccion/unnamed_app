<?php
use yii\helpers\Json;

$json_array = [];
foreach ($users as $user)
     $json_array[] = ['value'=>$user->full_name, 'label'=>$user->full_name];
echo Json::encode($json_array);
?>