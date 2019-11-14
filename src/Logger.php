<?php declare (strict_types = 1);

namespace Whchi\KafkaLogger;

use Kafka\Producer;
use Kafka\ProducerConfig;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractProcessingHandler;

class Logger extends AbstractProcessingHandler
{
    protected $config;

    public function __construct()
    {
        $this->bubble = false;
        $this->config = ProducerConfig::getInstance();
        $this->config->setMetadataRefreshIntervalMs(10000);
        $this->config->setMetadataBrokerList(config('kafkalogger.host'));
        $this->config->setBrokerVersion('2.1.0');
        $this->config->setRequiredAck(1);
        $this->config->setIsAsyn(false);
        $this->config->setProduceInterval(500);
    }

    protected function write(array $record): void
    {
        $producer = new Producer;
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

    protected function getDefaultFormatter(): FormatterInterface
    {
        return new Formatter;
    }
}
