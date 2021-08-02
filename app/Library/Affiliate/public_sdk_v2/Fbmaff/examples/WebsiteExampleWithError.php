<?php
require_once('../../autoload.php');

use \Fbmaff\Client;
use \Fbmaff\Transport;
use \Fbmaff\Api;
use \Fbmaff\Auth;

$hostname = 'https://api.affbackend.com'; // hostname for API backend
$username = ''; // set to your username
$api_key  = ''; // set to your api key

try
{
    $client = new Client\ApiClient(new Transport\Curl($hostname), new Auth\Basic($username, $api_key));

    $api = new Api\WebsiteApi($client, 2);
    $api->fetch();

    if (!$api->success() || !empty($api->getErrors()))
    {
        echo implode(PHP_EOL, $api->getErrors()) . PHP_EOL;
    }
    else
    {
        echo $api->getStatusMessage() . PHP_EOL;
        echo $api->getApiMessage() . PHP_EOL;
        $data = $api->getData();
        foreach ($data as $key => $value)
        {
            echo $key . ' = ' . $value . PHP_EOL;
        }
    }
}
catch (Exception $rex)
{
    echo 'Exception: ' . $rex->getMessage() . PHP_EOL;
}