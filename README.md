在 Laravel 裡使用 Log Facade 的直接送到 kafka
## 安裝步驟
1. composer
```bash
composer require whchi/laravel-kafkalogger "0.0.1"
```
2. publish vendor
```bash
php artisan vendor:publish --provider="Whchi\KafkaLogger\KafkaLogServiceProvider"
```
### log settings
* in `config/logging.php` add
```php
    'channels' => [
        ...
        'kafka' => [
            'driver' => 'custom',
            'via' => \App\Logging\KafkaHandler::class,
        ],
        ...
    ]
```
* add file `app/Logging/KafkaHandler.php`
```php
namespace App\Logging;

use Monolog\Logger;

class KafkaHandler
{
    public function __invoke()
    {
        $logger = new Logger('custom');
        $logger->pushHandler(resolve('KafkaLogger'));
        return $logger;
    }
}
```
* edit `.env`
```bash
LOG_CHANNEL=kafka
```
