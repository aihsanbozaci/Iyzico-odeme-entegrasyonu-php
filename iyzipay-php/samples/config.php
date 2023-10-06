<?php

require_once(dirname(__DIR__).'/IyzipayBootstrap.php');

IyzipayBootstrap::init();

class Config
{
    public static function options()
    {
        $options = new \Iyzipay\Options();
        $options->setApiKey('API key');
        $options->setSecretKey('Secret Key');
        $options->setBaseUrl('https://api.iyzipay.com'); //Sandbox hesabı için https://sandbox-api.iyzipay.com, gerçek hesap için https://api.iyzipay.com
        
        return $options;
    }
}