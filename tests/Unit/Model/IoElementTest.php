<?php

namespace Tests\Unit\Model;

use Uro\TeltonikaFmParser\Model\IoElement;


class IoElementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getValueProvider
     */
    public function testGetValue($value, $bytes, $format, $result)
    {
        $element = new IoElement(1, $value, $bytes);
        $method = 'getValueAs'.ucfirst($format);
    
        $this->assertEquals($element->{$method}(), $result);
    }

    public function getValueProvider()
    {
        return [
            ['81', 1, 'signed', -127],
            ['7f', 1, 'signed', 127],
            ['ff', 1, 'unsigned', 255],
            ['61', 1, 'string', 'a'],
            ['3e', 1, 'hex', '3e'],
            ['00ff', 2, 'signed', 255],
            ['ff01', 2, 'signed', -255],
            ['ffff', 2, 'unsigned', 65535],
            ['6161', 2, 'string', 'aa'],
            ['6161', 2, 'hex', '6161'],
            ['0000ffff', 4, 'signed', 65535],
            ['ffff0001', 4, 'signed', -65535],
            ['ffffffff', 4, 'unsigned', 4294967295],
            ['61616262', 4, 'string', 'aabb'],
            ['61616262', 4, 'hex', '61616262'],
            ['00000000ffffffff', 8, 'signed', 4294967295],
            ['ffffffff00000001', 8, 'signed', -4294967296],
            ['7fffffffffffffff', 8, 'unsigned', 9223372036854775807], 
            ['6161626263636464', 8, 'string', 'aabbccdd'],
            ['6161626263636464', 8, 'hex', '6161626263636464']
        ];
    }
}