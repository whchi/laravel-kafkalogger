使用 Log Facade 的直接送到 kafka

### 安裝步驟

1. `composer require cw/kafkalogger:1.0.0`
2. edit `config/app.php`, add ServiceProvider `Cw\KafkaLogger\KafkaLogServiceProvider::class,`
3. publish vendor `php artisan vendor:publish --provider="Cw\KafkaLogger\KafkaLogServiceProvider"` and change host & topic whatever you want
