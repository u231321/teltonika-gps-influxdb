<?php

namespace Uro\TeltonikaFmParser\Facade;

use Uro\TeltonikaFmParser\Model\ConfigurationPacket;

interface SmsEncoder
{
    public function encodePush();

    public function encodeConfiguration(ConfigurationPacket $configuration);
}