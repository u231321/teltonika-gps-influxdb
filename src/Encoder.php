<?php

namespace Uro\TeltonikaFmParser;

use Uro\TeltonikaFmParser\Model\ConfigurationPacket;

interface Encoder
{
    public function encodeAuthentication(bool $isAuthenticated): string;

    public function encodeData(int $numberOfRecords): string;

    public function encodeConfiguration(ConfigurationPacket $configuration);
}
