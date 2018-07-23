<?php

namespace Uro\TeltonikaFmParser\Model;

class Parameter
{
    protected $id;

    protected $value;

    public function __construct($id, $value)
    {
        $this->id = $id;
        $this->value = $value;
    }

    public function encode()
    {
        $hexValue = unpack('H*', $this->value)[1];
        
        return 
            padHex(dechex($this->id), 2).
            padHex((strlen($hexValue) / 2), 2).
            $hexValue;
    }
}