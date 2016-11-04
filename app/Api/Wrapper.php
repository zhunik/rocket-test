<?php

namespace Rocket\Api;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Rocket\ParserInterface;

/**
 * Class Wrapper
 *
 * Api wrapper for RockerRoute Public API
 *
 * @package Rocket
 */
class Wrapper
{
    /** @var  ClientInterface */
    private $_client;
    /** @var  ParserInterface */
    private $_parser;

    /** @var LoggerInterface */
    protected $logger;

    /**
     * @param ClientInterface $client
     * @param ParserInterface $parser
     * @param LoggerInterface $logger
     */
    public function __construct(ClientInterface $client, ParserInterface $parser, LoggerInterface $logger)
    {
        $this->_client = $client;
        $this->_parser = $parser;
        $this->logger = $logger;
    }

    /**
     * @param $icao string ICAO code.
     * @return mixed
     */
    public function getNotam($icao)
    {
        $request = $this->prepareNotamRequest($icao);
        $this->logger->log(LogLevel::INFO, $request);
        $response = $this->_client->getNotam($request);

        return $this->_parser->parse($response);
    }

    /**
     * @param $icao string ICAO code
     * @return string XML request string.
     */
    protected function prepareNotamRequest($icao)
    {
        $request = '<?xml version="1.0" encoding="UTF-8"?>
<REQWX>
<USR>%1$s</USR>
<PASSWD>%2$s</PASSWD>
<ICAO>%3$s</ICAO>
</REQWX>';

        return sprintf($request, API_USER, API_PASSWORD, $icao);
    }
}