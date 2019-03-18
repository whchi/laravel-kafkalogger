<?php

namespace Cw\KafkaLogger;

use Monolog\Handler\AbstractProcessingHandler;

class Logger extends AbstractProcessingHandler
{
    private $config;

    public function __construct()
    {
        $this->bubble = false;
        $this->config = \Kafka\ProducerConfig::getInstance();
        $this->config->setMetadataRefreshIntervalMs(10000);
        $this->config->setMetadataBrokerList(config('kafkalogger.host'));
        $this->config->setBrokerVersion('2.1.0');
        $this->config->setRequiredAck(1);
        $this->config->setIsAsyn(false);
        $this->config->setProduceInterval(500);
    }

    protected function write(array $record)
    {

        $producer = new \Kafka\Producer();
        $producer->send(
            [
                [
                    'topic' => config('kafkalogger.topic'),
                    'value' => $record['formatted'],
                    'key' => '',
                ],
            ]
        );
    }

    protected function getDefaultFormatter()
    {
        return new Formatter;
    }
}
