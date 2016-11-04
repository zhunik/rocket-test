<?php

namespace Rocket;


/**
 * Interface LocationConverterInterface
 * @package Rocket
 */
interface LocationConverterInterface
{
    /**
     * @param $degrees
     * @param $minutes
     * @param $seconds
     * @param $location
     * @return mixed
     */
    public function toDD($degrees, $minutes, $seconds, $location);
}