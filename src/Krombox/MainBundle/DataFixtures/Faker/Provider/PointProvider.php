<?php

namespace Krombox\MainBundle\DataFixtures\Faker\Provider;

class PointProvider
{
    public static function pointLatitude($lat = 49.5882670)
    {           
        $radius = 1; // Kilometers
        $earth_radius = 6371;
        
        $lat_max = $lat + rad2deg($radius / $earth_radius);
        $lat_min = $lat - rad2deg($radius / $earth_radius);
        return self::random($lat_min, $lat_max);
        
        $points = ['49.60379296','49.52452564','49.56184918','49.60157528','49.66760533','49.49620452','49.53803766','49.60183008','49.65504132','49.50268045'];
             
        return $points[array_rand($points)];
    }
    
    public static function pointLongitude()
    {
        mt_srand();
        return  mt_rand(345414170, 345614170) / 10000000;
        
        $points = ['34.51274782', '34.55017095','34.67240021','34.45589909','34.39062756','34.42724169','34.68013591','34.43985007','34.67223187','34.56067756'];
        
        return $points[array_rand($points)];
    }
    
    static protected function count_decimals($x)
    {
        return  strlen(substr(strrchr($x+"", "."), 1));
    }

    static protected function random($min, $max){
        mt_srand();
        $decimals = max(self::count_decimals($min), self::count_decimals($max));
        $factor = pow(10, $decimals);
        return rand($min*$factor, $max*$factor) / $factor;
    }

}