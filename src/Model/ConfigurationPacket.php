<?php

namespace Uro\TeltonikaFmParser\Model;

class ConfigurationPacket
{
    protected $id;

    protected $parameters;

    public function __construct($id, array $parameters)
    {
        $this->id = $id;
        $this->parameters = $parameters;
    }

    public function getId()
    {
        return $this->id;
    }

    public function encode()
    {
        $params = '';
        foreach($this->parameters as $param) {
            $params .= $param->encode();
        }

        $packet = 
            padHex($this->id, 1).
            padHex(dechex(count($this->parameters)), 2).
            $params;

        return 
            padHex(strlen($packet) / 2, 2).
            $packet;
    }
}