<?php 
    $i = 0;
    $len = count($bike_keepers);
    echo("{".'"type": "FeatureCollection","features":[');
    foreach ($bike_keepers as $bike_keeper){
        $terminator = ($i<$len-1)?',':'';
        echo($bike_keeper->toFeature().$terminator);
        $i++;
    }
    echo("]}");
?>
        
        