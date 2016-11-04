<?php

namespace Rocket;

/**
 * Interface ParserInterface
 * @package Rocket
 */
interface ParserInterface
{
    /**
     * @param $string
     * @return mixed
     */
    public function parse($string);
}