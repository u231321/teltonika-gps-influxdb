<?php

namespace Uro\TeltonikaFmParser;

use Uro\TeltonikaFmParser\Model\ConfigurationPacket;
use Uro\TeltonikaFmParser\Support\Hexadecimal as Hex;

class SmsEncoder implements Facade\SmsEncoder
{
    protected $config = [
        'wdpPushPort' => 2005,
        'smsLogin' => '',          // Device identifier (Can be set under SMS > Login using configurator)
        'smsPassword' => '',       // Device password (Can be set under SMS > Password using configurator)
        'serverHost' => '',
        'serverPort' => 0,
        'apnAddress' => '',
        'gprsLogin' => '',
        'gprsPassword' => ''
    ];

    protected $smsLogin;

    protected $smsLoginLength;

    protected $smsPassword;

    protected $smsPasswordLength;

    public function __construct(array $config = [])
    {
        foreach($config as $key => $value) {
            if(array_key_exists($key, $this->config)) {
                $this->config[$key] = $value;
            }
        }


        $this->smsLogin = Hex::fromString($this->config['smsLogin']);
        $this->smsLoginLength = Hex::fromInteger(strlen($this->smsLogin) / 2)->pad(1);
        $this->smsPassword = Hex::fromString($this->config['smsPassword']);
        $this->smsPasswordLength = Hex::fromInteger(strlen($this->smsPassword) / 2)->pad(1);
    }

    public function encodePush()
    {
        $host = Hex::fromString($this->config['serverHost']);
        $hostLength = Hex::fromInteger(strlen($host) / 2)->pad(1);
        $port = Hex::fromInteger($this->config['serverPort'])->pad(2);
        $apn = Hex::fromString($this->config['apnAddress']);
        $apnLength = Hex::fromInteger(strlen($apn) / 2)->pad(1);
        $login = Hex::fromString($this->config['gprsLogin']);
        $loginLength = Hex::fromInteger(strlen($login) / 2)->pad(1);
        $password = Hex::fromString($this->config['gprsPassword']);
        $passwordLength = Hex::fromInteger(strlen($password) / 2)->pad(1);

        return 
            $this->getHeader().
            $this->getCredentials().
            $hostLength.
            $host.
            $port.
            $apnLength.
            $apn.
            $loginLength.
            $login.
            $passwordLength.
            $password;
    }

    public function encodeConfiguration(ConfigurationPacket $configuration)
    {
        $configData = $configuration->encode();

        $header = $this->getHeader();
        $credentials = $this->getCredentials();
        // Since one SMS can transfer at most 140 bytes, configuration data have to be split into multiple SMS.
        $parts = explode('.', chunk_split($configData, 280 - (6 + strlen($header) + strlen($credentials)), '.'));
        $totalParts = Hex::fromInteger(count($parts))->pad(1);
        
        $sms = [];
        foreach($parts as $id => $part) {
            if(!empty($part)) {
                $sms[] = 
                    $this->getHeader().                 // TP-UDH
                    $this->getCredentials().            // SMS Credentials
                    Hex::fromInteger($configuration->getId())->pad(1). // TransferId. Id unique for all messages of single configuration
                    $totalParts.                    // Total parts
                    Hex::fromInteger($id)->pad(1).         // Current part
                    $part;                          // Part of configuration data
            }
        }

        return $sms;
    }

    protected function getHeader()
    {
        return 
            '060504'.
            Hex::fromInteger($this->config['wdpPushPort'])->pad(2).
            '0000';
    }

    protected function getCredentials()
    {
        return
            $this->smsLoginLength.    // Login length
            $this->smsLogin.          
            $this->smsPasswordLength. // Password length
            $this->smsPassword;       
    }
}