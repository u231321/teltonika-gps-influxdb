<?php

namespace Uro\TeltonikaFmParser\Model;

use JsonSerializable;

class IoElement implements JsonSerializable
{
    /**
     * Id of the element
     *
     * @var number
     */
    private $id;

    /**
     * Value in hexadecimal
     *
     * @var string
     */
    private $value;

    /**
     * Number of bytes of the element value
     *
     * @var number
     */
    private $bytes;

    public function __construct($id, $value, $bytes)
    {
        $this->id = $id;
        $this->value = $value;
        $this->bytes = $bytes;
    }

    public function getValueAsUnsigned()
    {
        switch($this->bytes) {
            case 1:
                $format = 'C';
                break;
            case 2:
                $format = 'S';
                break;
            case 4:
                $format = 'L';
                break;
            case 8:
                $format = 'J';
                break;
        }
        
        return $this->format($format);
    }

    public function getValueAsSigned()
    {
        switch($this->bytes) {
            case 1:
                $format = 'c';
                break;
            case 2:
                $format = 's';
                break;
            case 4:
                $format = 'l';
                break;
            case 8:
                $format = 'q';
                break;
        }
        
        return $this->format($format);
    }

    public function getValueAsString()
    {
        $string = '';
        for ($i = 0; $i < strlen($this->value) - 1; $i += 2){
            $string .= chr(hexdec($this->value[$i].$this->value[$i + 1]));
        }

        return $string;
    }

    public function getValueAsHex()
    {
        return $this->value;
    }  

    /**
     * Format element value using the specified formad
     *
     * @param string $format
     * @return void
     */
    protected function format(string $format)
    {
        return unpack($format, pack($format, hexdec($this->value)))[1];
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'value' => $this->value
        ];
    }
}