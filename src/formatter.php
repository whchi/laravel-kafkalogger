<?php declare (strict_types = 1);
/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cw\KafkaLogger;

use DateTime;
use Exception;
use InvalidArgumentException;
use Monolog\Formatter\NormalizerFormatter;
use Throwable;

/**
 * Format a log message into an Elasticsearch record
 *
 * @author Avtandil Kikabidze <akalongman@gmail.com>
 */
class Formatter extends NormalizerFormatter
{
    protected $hostname;
    protected $extraPrefix;
    protected $contextPrefix;
    protected $includeStacktraces;

    /**
     * elk fields.type
     *
     * @var [string]
     */
    protected $type;
    public function __construct()
    {
        // Elasticsearch requires an ISO 8601 format date with optional millisecond precision.
        parent::__construct(DateTime::ISO8601);
        $this->hostname = php_uname('n');
        $this->extraPrefix = 'extra_';
        $this->contextPrefix = 'ctx_';
        $this->type = preg_replace('/(http:\/\/)|(https:\/\/)/i', '', config('app.url'));
    }

    /**
     * {@inheritdoc}
     */
    public function format(array $record): string
    {
        $record = parent::format($record);
        $record = $this->formatToELKLogStash($record);
        return $this->toJson($record);
    }

    protected function formatToELKLogStash(array $record): array
    {
        if (empty($record['datetime'])) {
            $record['datetime'] = gmdate('c');
        }
        $message = array(
            '@timestamp' => $record['datetime'],
            '@version' => 1,
            'host' => $this->hostname,
        );
        if (isset($record['message'])) {
            $message['message'] = $record['message'];
        }
        if (isset($record['channel'])) {
            $message['type'] = $record['channel'];
            $message['channel'] = $record['channel'];
        }
        if (isset($record['level_name'])) {
            $message['level'] = $record['level_name'];
        }
        if (!empty($record['extra'])) {
            foreach ($record['extra'] as $key => $val) {
                $message[$this->extraPrefix . $key] = $val;
            }
        }
        if (!empty($record['context'])) {
            foreach ($record['context'] as $key => $val) {
                $message[$this->contextPrefix . $key] = $val;
            }
        }

        $message += [
            'fields' => [
                'tag' => 'laravel',
                'type' => 'laravel-log-' . $this->type,
            ],
            'input' => ['type' => 'log'],
            'queryString' => request()->server('REQUEST_URI'),
            'requestType' => 'http'];
        if (strpos(php_sapi_name(), 'cli') !== false) {
            $message['requestType'] = 'cmd';
        }
        return $message;
    }

    protected function normalizeException(Throwable $e, int $depth = 0)
    {
        // TODO 2.0 only check for Throwable
        if (!$e instanceof Exception && !$e instanceof Throwable) {
            throw new InvalidArgumentException('Exception/Throwable expected, got ' . gettype($e) . ' / ' . get_class($e));
        }

        $previousText = '';
        if ($previous = $e->getPrevious()) {
            do {
                $previousText .= ', ' . get_class($previous) . '(code: ' . $previous->getCode() . '): ' . $previous->getMessage() . ' at ' . $previous->getFile() . ':' . $previous->getLine();
            } while ($previous = $previous->getPrevious());
        }

        $str = '[object] (' . get_class($e) . '(code: ' . $e->getCode() . '): ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine() . $previousText . ')';
        if ($this->includeStacktraces) {
            $str .= "\n[stacktrace]\n" . $e->getTraceAsString() . "\n";
        }

        return $str;
    }

}
