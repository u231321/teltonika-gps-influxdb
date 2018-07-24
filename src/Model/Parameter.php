<?php

namespace Uro\TeltonikaFmParser\Model;

use Uro\TeltonikaFmParser\Support\Hexadecimal as Hex;

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
            Hex::fromInteger($this->id)->pad(2).
            Hex::fromInteger(strlen($hexValue) / 2)->pad(2).
            $hexValue;
    }
}