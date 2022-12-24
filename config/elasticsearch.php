<?php
use Monolog\Logger;

return [
    'hosts' => [
        'localhost:9200'
    ],
    'logging' => true,
    'logPath' => storage_path() . '/logs/elasticsearch-' . php_sapi_name() . '.log',
    'logPath' => storage_path() . '/logs/elasticsearch.log',
    'logLevel' => Logger::INFO,
    'logLevel' => Logger::DEBUG
];
