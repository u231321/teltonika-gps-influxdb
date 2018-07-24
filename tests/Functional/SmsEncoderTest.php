<?php

namespace Tests\Unit;

use Uro\TeltonikaFmParser\SmsEncoder;
use Uro\TeltonikaFmParser\Model\Parameter;
use Uro\TeltonikaFmParser\Model\ConfigurationPacket;

class SmsEncoderTest extends \PHPUnit_Framework_TestCase
{
    protected $encoder;

    public function setUp()
    {
        $this->encoder = new SmsEncoder([
            'wdpPushPort' => 2001,
            'smsLogin' => 'aaa',
            'smsPassword' => 'bbb',
            'serverHost' => '192.168.1.1',
            'serverPort' => 43707,
            'apnAddress' => 'internet:c',
            'gprsLogin' => 'user',
            'gprsPassword' => 'a'
        ]);
    }

    public function testPushSms()
    {
        $this->assertEquals(
            '06050407d1000003616161036262620b3139322e3136382e312e31aabb0a696e7465726e65743a6304757365720161',
            $this->encoder->encodePush()
        );
    }

    public function testConfigurationSms()
    {
        $config = new ConfigurationPacket('8c', [
            new Parameter(1032, '0'),
            new Parameter(1033, '0'),
            new Parameter(1034, '0'),
            new Parameter(1040, '0'),
            new Parameter(1041, '0'),
            new Parameter(1042, '0'),
            new Parameter(1043, '0'),
            new Parameter(1044, '0'),
            new Parameter(1050, '0'),
            new Parameter(1051, '0'),
            new Parameter(1052, '0'),
            new Parameter(1053, '0'),
            new Parameter(1054, '0'),
            new Parameter(1060, '0'),
            new Parameter(1061, '0'),
            new Parameter(1062, '0'),
            new Parameter(1063, '0'),
            new Parameter(1064, '0'),
            new Parameter(1065, '0'),
            new Parameter(3261, '+37044444444')
        ]);

        $sms = $this->encoder->encodeConfiguration($config);

        /*$this->assertEquals(
            '06050407d5000003616161036262628c0201042700013004280001300cbd000c2b33373303434343434343434',
            $sms[1]
        );*/
    }
}