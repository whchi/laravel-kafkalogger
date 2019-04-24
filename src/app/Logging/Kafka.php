<?php

namespace App\Logging;

use Monolog\Logger;

class KafkaHandler
{
    public function __invoke()
    {
        $logger = new Logger('custom');
        $logger->pushHandler(\App::make('KafkaLogger'));

        return $logger;
    }
}
