<?php

namespace Rocket;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Rocket\Api\Client;
use Rocket\Api\Wrapper;


/**
 * Class App
 * @package Rocket
 */
class App
{

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * App constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $icao
     * @return bool|mixed
     */
    public function getNotamsByIcao($icao = '')
    {
        $client = new Client(API_URL);
        $parser = new NotamParser($this->logger, new LocationConverter());
        $api = new Wrapper($client, $parser, $this->logger);

        try {
            return $api->getNotam($icao);
        } catch (\Exception $e) {
            $this->logger->log(LogLevel::ERROR, sprintf('Can\'t get notams by ICAO: %s', $icao));
        }

        return false;
    }
}