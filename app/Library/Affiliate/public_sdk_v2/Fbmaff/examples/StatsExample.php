<?php
require_once('../../autoload.php');

use \Fbmaff\Client;
use \Fbmaff\Transport;
use \Fbmaff\Api;
use \Fbmaff\Auth;

$hostname = 'https://api.affbackend.com';
$username = ''; // set to your username
$api_key  = ''; // set to your api key

try
{
    $client = new Client\ApiClient(new Transport\Curl($hostname), new Auth\Basic($username, $api_key));

    $api = new Api\StatsApi($client, 2); // version 2
    $api->setProgram('PPP')
        ->setDates('2016-01-01', '2016-08-02')
        ->setPlatform('singlescash.com')
        ->setGroupBy(['program'])
        ->setSortBy([
            'profile' => 'DESC',
        ])
        ->fetch();

    
    if (!$api->success() || !empty(count($api->getErrors())))
    {
        echo implode(PHP_EOL, $api->getErrors()) . PHP_EOL;
    }
    else
    {
        echo $api->getStatusMessage() . PHP_EOL;
        echo $api->getApiMessage() . PHP_EOL;
        $data = $api->getData();
        if (empty($data))
        {
            echo 'No data' . PHP_EOL;
        }
        else
        {
            echo implode("\t", array_keys($data[0])) . PHP_EOL;
            foreach ($data as $row)
            {
                echo implode("\t", $row) . PHP_EOL;
            }
        }
    }

    $api->fetchFull();
    if (!$api->success() || !empty(count($api->getErrors())))
    {
        echo implode(PHP_EOL, $api->getErrors()) . PHP_EOL;
    }
    else
    {
        echo $api->getStatusMessage() . PHP_EOL;
        echo $api->getApiMessage() . PHP_EOL;
        $data = $api->getData();

        if (empty($data['data']))
        {
            echo 'No data';
        }
        else
        {
            echo 'Data:' . PHP_EOL;
            echo implode("\t", array_keys($data['data'][0])) . PHP_EOL;
            foreach ($data['data'] as $row)
            {
                echo implode("\t", $row) . PHP_EOL;
            }
            echo PHP_EOL . 'Page totals:' . PHP_EOL;
            foreach ($data['page_totals'] as $field => $value)
            {
                echo $field . ' = ' . $value . PHP_EOL;
            }
            echo PHP_EOL . 'Totals:' . PHP_EOL;
            foreach ($data['full_totals'] as $field => $value)
            {
                echo $field . ' = ' . $value . PHP_EOL;
            }
        }
    }

    $api->setCompareDates('2015-01-01', '2015-12-31')->fetchWithCompare();
    if (!$api->success() || !empty(count($api->getErrors())))
    {
        echo implode(PHP_EOL, $api->getErrors()) . PHP_EOL;
    }
    else
    {
        echo $api->getStatusMessage() . PHP_EOL;
        echo $api->getApiMessage() . PHP_EOL;
        $data = $api->getData();

        if (empty($data['data']))
        {
            echo 'No data';
        }
        else
        {
            echo 'Data:' . PHP_EOL;
            echo implode("\t", array_keys($data['data'][0])) . PHP_EOL;
            foreach ($data['data'] as $row)
            {
                foreach ($row as $value)
                {
                    echo is_array($value) ? implode(' | ', $value) : $value ;
                    echo "\t";
                }
                echo PHP_EOL;
            }
            echo PHP_EOL . 'Totals:' . PHP_EOL;
            foreach ($data['full_totals'] as $field => $value)
            {
                echo $field . ' = ' . $value[0] . ' | ' . $value[1] . ' | ' . $value[2] . PHP_EOL;
            }
        }
    }
}
catch (Exception $rex)
{
    echo 'Exception: ' . $rex->getMessage() . PHP_EOL;
}