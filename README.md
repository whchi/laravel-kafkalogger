使用 Log Facade 的直接送到 kafka

### 安裝步驟
1. setup repo uri `composer config repositories.kafkalogger vcs git@ssh.dev.azure.com:v3/cwgroup/digital-products/kafkalogger`
2. `composer require cw/kafkalogger "1.0.*"`
3. edit `config/app.php`, add ServiceProvider `Cw\KafkaLogger\KafkaLogServiceProvider::class,`
4. publish vendor `php artisan vendor:publish --provider="Cw\KafkaLogger\KafkaLogServiceProvider"` and change host & topic whatever you want
