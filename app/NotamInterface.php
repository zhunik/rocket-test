<?php

namespace Rocket;


/**
 * Interface NotamInterface
 * @package Rocket
 */
interface NotamInterface
{
    /**
     * @return LocationInterface
     */
    public function getLocation();

    /**
     * @return string
     */
    public function getDescription();

}