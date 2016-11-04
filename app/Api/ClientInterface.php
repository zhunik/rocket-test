<?php

namespace Rocket\Api;

use Rocket\NotamInterface;

/**
 * Interface ClientInterface
 * @package Rocket\Api
 */
interface ClientInterface
{

    /**
     * @param $request string
     * @return NotamInterface
     */
    public function getNotam($request);

}