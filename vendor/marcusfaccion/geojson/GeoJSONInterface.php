<?php

namespace marcusfaccion\geojson;
/**
 * 
 */
interface GeoJSONInterface 
{
    
    public function toGeoJSON();
    
    public function toFeature();
    public function toArray();
}



