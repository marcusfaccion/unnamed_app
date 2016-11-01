<?php 
    $i = 0;
    $len = count($alerts);
    echo("{".'"type": "FeatureCollection","features":[');
    foreach ($alerts as $alert){
        $terminator = ($i<$len-1)?',':'';
        echo($alert->toFeature().$terminator);
        $i++;
    }
    echo("]}");
?>
        
        