<?php

namespace Cw\KafkaLogger;

use Monolog\Handler\AbstractProcessingHandler;

class Logger extends AbstractProcessingHandler
{

    public function __construct()
    {
        $this->bubble = false;
    }

    protected function write(array $record)
    {
        $config = \Kafka\ProducerConfig::getInstance();
        $config->setMetadataRefreshIntervalMs(10000);
        $config->setMetadataBrokerList(env('KAFKA_HOST', '127.0.0.1:9092'));
        $config->setBrokerVersion('2.1.0');
        $config->setRequiredAck(1);
        $config->setIsAsyn(false);
        $config->setProduceInterval(500);
        $producer = new \Kafka\Producer();
        $producer->send([
            [
                'topic' => env('KAFKA_TOPIC', 'logstash'),
                'value' => $record['formatted'],
                'key' => '',
            ],
        ]);

    }

    protected function getDefaultFormatter()
    {
        return new Formatter;
    }
}
