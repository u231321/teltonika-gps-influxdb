<?php

namespace Uro\TeltonikaFmParser\Model;

use Uro\TeltonikaFmParser\Support\Hexadecimal as Hex;

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
            (new Hex($this->id))->pad(1).
            Hex::fromInteger(count($this->parameters))->pad(2).
            $params;

        return 
            Hex::fromInteger(strlen($packet) / 2)->pad(2).
            $packet;
    }
}