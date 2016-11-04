<?php

namespace Rocket;

/**
 * Interface LocationInterface
 * @package Rocket
 */
interface LocationInterface
{
    /**
     * @return float
     */
    public function getLatitude();

    /**
     * @return float
     */
    public function getLongtitude();
}
