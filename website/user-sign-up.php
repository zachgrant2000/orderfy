<?php
/**
 * Created by PhpStorm.
 * User: zwg2
 * Date: 11/22/14
 * Time: 4:05 PM
 */
$ php -a;

require "vendor/autoload.php";
$apiKeyFile = $_SERVER["HOME"] .  "/.stormpath/apiKey.properties.txt";
$builder = new \Stormpath\ClientBuilder();
$client = $builder->setApiKeyFileLocation($apiKeyFile)->build();