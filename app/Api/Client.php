<?php

namespace Rocket\Api;


class Client implements ClientInterface
{

    public function __construct($client_url)
    {
        $this->client = new \SoapClient($client_url);
    }


    /**
     * @param $request string XML request
     * @return string
     */
    public function getNotam($request)
    {
        return $this->client->getNotam($request);
    }

}