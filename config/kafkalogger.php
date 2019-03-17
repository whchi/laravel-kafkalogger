<?php

return [
    'host' => env('KAFKA_HOST', '127.0.0.1:9092'),
    'topic' => env('KAFKA_TOPIC', 'logstash')
];
