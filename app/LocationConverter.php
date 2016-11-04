<?php

namespace Rocket;


/**
 * Class LocationConverter
 * @package Rocket
 */
class LocationConverter implements LocationConverterInterface
{

    /**
     * Convert DMS location do DD
     *
     * @param $degrees int
     * @param $minutes int
     * @param $seconds int
     * @param $location int // (latitude: N = 1 ,S = -1 ; longtitude: W = -1 , E = 1 )
     * @return double
     */
    public function toDD($degrees, $minutes, $seconds, $location)
    {
        return ($degrees + ($minutes / 60) + ($seconds / 3600)) * $location;
    }


}