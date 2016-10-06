<?php
/**
 * Laravel queue driver bases on Aliyun Message Service（MNS）
 *
 * @author yedonghai <progpark@outlook.com>
 * @version 1.0.0
 * @copyright (c) MainPHP, 06 October, 2016
 * @package aliyun-mns-laravel
 * @link: https://github.com/progpark/aliyun-mns-laravel
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace MainPHP\Laravel\Aliyun;

use Illuminate\Queue\Connectors\ConnectorInterface;
use Illuminate\Support\Facades\Config;
use AliyunMNS\Client as MnsClient;
use MainPHP\Laravel\Aliyun\MnsAdapter;
use MainPHP\Laravel\Aliyun\MnsQueue;

class MnsConnector implements ConnectorInterface
{
    /**
     * Establish a queue connection.
     *
     * @param array $config
     *
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        $config = Config::get('queue.connections.alimns');

        return new MnsQueue(
            new MnsAdapter(new MnsClient($config['endpoint'], $config['key'], $config['secret']), $config['queue'])
        );
    }
}
