<?php

namespace Tests\Unit\Model;

use Uro\TeltonikaFmParser\Model\Parameter;


class ParameterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider encodeDataProvider
     */
    public function testParameterEncode($id, $value, $result)
    {
        $param = new Parameter($id, $value);
        $encoded = $param->encode();

        $this->assertEquals(
            $encoded,
            $result
        );
    }

    public function encodeDataProvider()
    {
        return [
            [1000, 0, '03e8000130'],
            [1011, 20, '03f300023230']
        ];
    }
}