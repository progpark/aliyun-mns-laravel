# Laravel-MNS

[![StyleCI PSR2](https://styleci.io/repos/57226401/shield)](https://styleci.io/repos/57226401)
[![Build Status](https://travis-ci.org/abrahamgreyson/laravel-mns.svg?branch=master)](https://travis-ci.org/abrahamgreyson/laravel-mns)
[![Code Coverage](https://scrutinizer-ci.com/g/abrahamgreyson/laravel-mns/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/abrahamgreyson/laravel-mns/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/abe/laravel-mns/v/stable)](https://packagist.org/packages/abe/laravel-mns)
[![License](https://img.shields.io/badge/license-MIT-000000.svg)](https://packagist.org/packages/abe/laravel-mns)

阿里云消息服务（MNS）的 Laravel 适配，本质上是为 Laravel 的队列增加 MNS 驱动。包含了阿里云 MNS SDK，为了 Laravel 能透明的使用 MNS 而对其作必要的引用。

## 安装使用
通过 Composer 安装：
```
$ composer require progpark/aliyun-mns-laravel
```
将驱动注册到常用的 ServiceProvider 中：
```
Queue::extend('mns', function() {
    return new \Progpark\AliMNS\Connectors\MnsConnector();
});
```
之后在 config/queue.php 中增加 `mns` 配置：
```
'connections' => [
    'redis' => [
        'driver'     => 'redis',
        'connection' => 'default',
        'queue'      => 'default',
        'expire'     => 60,
    ],

    // 新增阿里云 MNS。
    'mns'   => [
        'driver'   => 'mns',
        'key'      => env('MNS_ACCESS_KEY', 'access-key'),
        'secret'   => env('MNS_SECRET_KEY', 'secret-key'),
        'endpoint' => 'your-endpoint', // 外网连接必须启用 https
        'queue'    => env('MNS_DEFAULT_QUEUE', 'default-queue-name'),
    ],
],
```
正常使用 Laravel Queue 即可：
[https://laravel.com/docs/5.2/queues](https://laravel.com/docs/5.3/queues)

## 许可
MIT

