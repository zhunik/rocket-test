<?php

namespace Rocket;


/**
 * Class Notam
 * @package Rocket
 */
class Notam implements NotamInterface, \JsonSerializable
{
    /** @var LocationInterface $_location */
    private $_location;
    /** @var string $_description */
    private $_description;


    /**
     * Notam constructor.
     * @param LocationInterface $location
     * @param string $description
     */
    public function __construct(LocationInterface $location, $description = '')
    {
        $this->_location = $location;
        $this->_description = $description;
    }

    /**
     * @return LocationInterface
     */
    public function getLocation()
    {
        return $this->_location;
    }


    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode([
            'lat'  => $this->_location->getLatitude(),
            'long' => $this->_location->getLongtitude(),
            'desc' => $this->_description,
        ]);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'lat'  => $this->_location->getLatitude(),
            'long' => $this->_location->getLongtitude(),
            'desc' => $this->_description,
        ];
    }
}