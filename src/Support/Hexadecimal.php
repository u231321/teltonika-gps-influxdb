<?php

namespace Uro\TeltonikaFmParser\Support;

class Hexadecimal
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function pad($bytes)
    {
        $this->value = str_pad($this->value, $bytes * 2, '0', STR_PAD_LEFT);

        return $this;
    }

    public function __toString() : string
    {
        return $this->value;
    }

    public static function fromInteger($integer)
    {
        return new Hexadecimal(dechex($integer));
    }

    public static function fromString(string $string) : Hexadecimal
    {
        $hex='';
        for ($i=0; $i < strlen($string); $i++){
            $hex .= dechex(ord($string[$i]));
        }

        return new Hexadecimal($hex);
    }
}