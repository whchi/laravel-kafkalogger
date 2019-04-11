使用 Log Facade 的直接送到 kafka

### 安裝步驟
1. setup repo uri
```bash
composer config repositories.kafkalogger vcs git@ssh.dev.azure.com:v3/cwgroup/digital-products/kafkalogger
```
2. require package
```bash
composer require cw/kafkalogger "1.0.*"
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
5. set host & topic
