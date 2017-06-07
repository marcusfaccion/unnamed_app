<?php 
    $i = 0;
    $len = count($routes);
    echo("{".'"type": "FeatureCollection","features":[');
    foreach ($routes as $route){
        $terminator = ($i<$len-1)?',':'';
        echo($route->toFeature().$terminator);
        $i++;
    }
    echo("]}");
?>
        
        