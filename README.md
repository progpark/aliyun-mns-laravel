# aliyun-mns-laravel
组件为 Laravel 的队列增加 MNS 驱动，引入了 Aliyun MNS SDK。

## 安装使用
首先，通过 Composer 安装扩展组件：
```
$ composer require progpark/aliyun-mns-laravel
```
其次，在`app\Http\Providers\AppServiceProvider.php`中，将驱动注册到常用 ServiceProvider 中：
```
Queue::extend('alimns', function() {
    return new \MainPHP\Laravel\Aliyun\MnsConnector();
});
```
最后、在 config/queue.php 中增加队列驱动 `alimns`：
```
'connections' => [
    'redis' => [
        'driver'     => 'redis',
        'connection' => 'default',
        'queue'      => 'default',
        'expire'     => 60,
    ],

    // Aliyun MNS Driver
    'alimns'   => [
        'driver'   => 'alimns',
        'queue'    => env('MNS_DEFAULT_QUEUE', ''),
        'key'      => env('MNS_ACCESS_KEY', ''),
        'secret'   => env('MNS_SECRET_KEY', ''),
        'endpoint' => env('MNS_ENDPOINT', ''), // 外网连接必须启用 https
    ],
],
```
正常使用 Laravel Queue 即可：
[https://laravel-china.org/docs/5.3/queues](https://laravel-china.org/docs/5.3/queues)
