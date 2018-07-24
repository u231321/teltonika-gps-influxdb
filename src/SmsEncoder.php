<?php

namespace Uro\TeltonikaFmParser;

use Uro\TeltonikaFmParser\Model\ConfigurationPacket;

class SmsEncoder implements Facade\SmsEncoder
{
    protected $config = [
        'wdpPushPort' => 2005,
        'smsLogin' => '',          // Device identifier (Can be set under SMS > Login using configurator)
        'password' => '',       // Device password (Can be set under SMS > Password using configurator)
        'serverHost' => '',
        'serverPort' => 0,
        'apnAddress' => '',
        'gprsLogin' => '',
        'gprsPassword' => ''
    ];

    protected $smsLogin;

    protected $smsLoginLength;

    protected $password;

    protected $passwordLength;

    public function __construct(array $config = [])
    {
        foreach($config as $key => $value) {
            if(array_key_exists($key, $this->config)) {
                $this->config[$key] = $value;
            }
        }


        $this->smsLogin = str2hex($this->config['smsLogin']);
        $this->smsLoginLength = padHex(dechex(strlen($this->smsLogin) / 2), 1);
        $this->password = str2hex($this->config['password']);
        $this->passwordLength = padHex(dechex(strlen($this->password) / 2), 1);
    }

    public function encodePush()
    {
        $host = str2hex($this->config['serverHost']);
        $hostLength = padHex(dechex(strlen($host) / 2), 1);
        $port = padHex(dechex($this->config['serverPort']), 2);
        $apn = str2hex($this->config['apnAddress']);
        $apnLength = padHex(dechex(strlen($apn) / 2), 1);
        $login = str2hex($this->config['gprsLogin']);
        $loginLength = padHex(dechex(strlen($login) / 2), 1);
        $password = str2hex($this->config['gprsPassword']);
        $passwordLength = padHex(dechex(strlen($password) / 2), 1);

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
        $totalParts = padHex(dechex(count($parts)), 1);
        
        $sms = [];
        foreach($parts as $id => $part) {
            if(!empty($part)) {
                $sms[] = 
                    $this->getHeader().                 // TP-UDH
                    $this->getCredentials().            // SMS Credentials
                    padHex($configuration->getId(), 1). // TransferId. Id unique for all messages of single configuration
                    $totalParts.                    // Total parts
                    padHex(dechex($id), 1).         // Current part
                    $part;                          // Part of configuration data
            }
        }

        return $sms;
    }

    protected function getHeader()
    {
        return 
            '060504'.
            padHex(dechex($this->config['wdpPushPort']), 2).
            '0000';
    }

    protected function getCredentials()
    {
        return
            $this->smsLoginLength.    // Login length
            $this->smsLogin.          
            $this->passwordLength. // Password length
            $this->password;       
    }
}