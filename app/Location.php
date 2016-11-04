<?php

namespace Rocket;

/**
 * Class Location
 * @package Rocket
 */
class Location implements LocationInterface
{
    /** @var $_latitude double */
    private $_latitude;
    /** @var $latitude double */
    private $_longtitude;

    /**
     * Location constructor.
     * @param $latitude
     * @param $longtitude
     */
    public function __construct($latitude, $longtitude)
    {
        $this->_latitude = $latitude;
        $this->_longtitude = $longtitude;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->_latitude;
    }

    /**
     * @return float
     */
    public function getLongtitude()
    {
        return $this->_longtitude;
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return "latitude: {$this->_latitude}; longtitude: {$this->_longtitude}";
    }

}