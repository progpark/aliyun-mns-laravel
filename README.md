# aliyun-mns-laravel

[![StyleCI PSR2](https://styleci.io/repos/57226401/shield)](https://styleci.io/repos/57226401)
[![Build Status](https://travis-ci.org/abrahamgreyson/laravel-mns.svg?branch=master)](https://travis-ci.org/abrahamgreyson/laravel-mns)
[![Code Coverage](https://scrutinizer-ci.com/g/abrahamgreyson/laravel-mns/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/abrahamgreyson/laravel-mns/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/abe/laravel-mns/v/stable)](https://packagist.org/packages/abe/laravel-mns)
[![License](https://img.shields.io/badge/license-MIT-000000.svg)](https://packagist.org/packages/abe/laravel-mns)

组件是为 Laravel 的队列增加 MNS 驱动，引入了 Aliyun MNS SDK。

## 安装使用
通过 Composer 安装：
```
$ composer require progpark/aliyun-mns-laravel
```
将驱动注册到常用的 ServiceProvider 中：
```
Queue::extend('mns', function() {
    return new \MainPHP\Laravel\Aliyun\MnsConnector();
});
```
之后在 config/queue.php 中增加 `alimns` 配置：
```
'connections' => [
    'redis' => [
        'driver'     => 'redis',
        'connection' => 'default',
        'queue'      => 'default',
        'expire'     => 60,
    ],

    // 新增阿里云 MNS。
    'alimns'   => [
        'driver'   => 'alimns',
        'key'      => env('MNS_ACCESS_KEY', 'access-key'),
        'secret'   => env('MNS_SECRET_KEY', 'secret-key'),
        'endpoint' => 'your-endpoint', // 外网连接必须启用 https
        'queue'    => env('MNS_DEFAULT_QUEUE', 'default-queue-name'),
    ],
],
```
正常使用 Laravel Queue 即可：
[https://laravel.com/docs/5.3/queues](https://laravel.com/docs/5.3/queues)

## 许可
MIT

