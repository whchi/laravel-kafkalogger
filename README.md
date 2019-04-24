使用 Log Facade 的直接送到 kafka

### 安裝步驟
1. setup repo uri
```bash
composer config repositories.kafkalogger vcs git@ssh.dev.azure.com:v3/cwgroup/digital-products/kafkalogger
```
2. require package
```bash
# Laravel 5.5
composer require cw/kafkalogger "1.0.*"
# Laravel 5.8
composer require cw/kafkalogger "dev-master"
```
3. vim
```bash
config/app.php
```
add ServiceProvider
```text
Cw\KafkaLogger\KafkaLogServiceProvider::class,
```
4. publish vendor
```bash
php artisan vendor:publish --provider="Cw\KafkaLogger\KafkaLogServiceProvider"
```
**In dev-master version this command will create a file at `app/Logging/Kafka.php`**

5. set host & topic in config/kafkalogger.php

### Laravel 5.8 log settings
in `config/logging.php` add
```php
    'channels' => [
        ...
        'custom' => [
            'driver' => 'custom',
            'via' => \App\Logging\Kafka::class,
        ],
        ...
    ]
```
edit `.env`
```bash
LOG_CHANNEL=custom
```
