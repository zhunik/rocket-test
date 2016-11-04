<?php

namespace Rocket;


use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Class NotamParser
 * @package Rocket
 */
class NotamParser implements ParserInterface
{
    /**
     * @var array Possible results defined by an API vendor.
     */
    private $results = [
        'OK',
        'No NOTAM found',
        'API request version incorrect',
        'Client query incomplete or defective',
        'Incorrect username or password',
        'Unknown error on server side',
    ];

    /**
     * @var string XSD schema location
     */
    private $schemaFile;

    /**
     * @var LocationConverterInterface
     */
    private $converter;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * NotamParser constructor.
     * @param LoggerInterface $logger
     * @param LocationConverterInterface $converter
     */
    public function __construct(LoggerInterface $logger, LocationConverterInterface $converter)
    {
        $this->logger = $logger;
        $this->converter = $converter;
        $this->schemaFile = NOTAM_SCHEMA_FILE;
    }


    /**
     * @param $response
     * @return array|bool
     */
    public function parse($response)
    {
        $this->logger->log(LogLevel::INFO, $response);
//        libxml_use_internal_errors(true);
        $notamXml = new \DOMDocument();
        $notamXml->loadXML($response);
        if (!$notamXml->schemaValidate($this->schemaFile)) {
            $errors = libxml_get_errors();
            foreach ($errors as $error) {
                $this->logger->log(LogLevel::ERROR, $error->code);
            }
            libxml_clear_errors();
            $this->logger->log(LogLevel::ERROR, 'XML responce isn\'t valid!');

            return false;
        }

        $results = $notamXml->getElementsByTagName('RESULT');
        $result = intval($results->item(0)->nodeValue);

        if ($result) {
            $messge = 'Error! Bad api response!';
            if (array_key_exists($result, $this->results)) {
                $messge = $this->results[$result];
            }
            $this->logger->log(LogLevel::ERROR, $messge);

            return false;
        }

        $notams = $notamXml->getElementsByTagName('NOTAM');

        $parsedNotams = [];
        foreach ($notams as $notam) {
            $qItems = $notam->getElementsByTagName('ItemQ');
            $itemQ = $qItems->item(0)->nodeValue;
            $location = $this->parseLocation($itemQ);

            $eItems = $notam->getElementsByTagName('ItemE');
            $itemE = $eItems->item(0)->nodeValue;

            if ($location && $itemE) {
                $parsedNotams[] = new Notam($location, $itemE);
            }
        }

        return $parsedNotams;
    }


    /**
     * @param $itemQ
     * @return bool|Location
     */
    protected function parseLocation($itemQ)
    {
        $this->logger->log(LogLevel::INFO, 'ItemQ: '.$itemQ);
        if (preg_match('/\/(\d+)(N|S)(\d+)(E|W)/', $itemQ, $matches)) {
            $this->logger->log(LogLevel::INFO, 'Location matches : '.implode('; ', $matches));
            $lat['deg'] = intval(substr($matches[1], 0, 2));
            $lat['min'] = intval(substr($matches[1], 2, 2));
            $lat['sec'] = intval(substr($matches[1], 4));
            $lat['loc'] = ($matches[2] == 'N' ? 1 : -1);
            $long['deg'] = intval(substr($matches[3], 0, 3));
            $long['min'] = intval(substr($matches[3], 3, 2));
            $long['sec'] = intval(substr($matches[3], 5));
            $long['loc'] = ($matches[4] == 'E' ? 1 : -1);

            $latitude = $this->converter->toDD($lat['deg'], $lat['min'], $lat['sec'], $lat['loc']);
            $longtitude = $this->converter->toDD($long['deg'], $long['min'], $long['sec'], $long['loc']);

            $this->logger->log(LogLevel::INFO, 'Location lat: '.$latitude.', long:'.$longtitude);

            if ($latitude != 0 && $longtitude != 0) {
                return new Location($latitude, $longtitude);
            }

            return false;
        }

        return false;
    }


}