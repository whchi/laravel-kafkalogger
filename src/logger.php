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
        $config->setMetadataBrokerList(config('kafkalogger.host'));
        $config->setBrokerVersion('2.1.0');
        $config->setRequiredAck(1);
        $config->setIsAsyn(false);
        $config->setProduceInterval(500);
        $producer = new \Kafka\Producer();
        $producer->send([
            [
                'topic' => config('kafkalogger.topic'),
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
