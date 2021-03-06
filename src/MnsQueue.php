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

use AliyunMNS\Exception\MessageNotExistException;
use AliyunMNS\Requests\SendMessageRequest;
use Illuminate\Contracts\Queue\Queue as QueueContract;
use Illuminate\Queue\Queue;
use MainPHP\Laravel\Aliyun\MnsJob;

class MnsQueue extends Queue implements QueueContract
{
    /**
     * client
     *
     * @var MnsAdapter
     */
    protected $mns;

    /**
     * Custom callable to handle jobs
     *
     * @var callable
     */
    protected $jobCreator;

    /**
     * The name of default queue
     *
     * @var string
     */
    protected $default;

    /**
     * create a new MnsQueue instance
     *
     * @return void
     */
    public function __construct(MnsAdapter $mns)
    {
        $this->mns = $mns;

        $this->default = $this->mns->getQueueName();
    }

    /**
     * Get the size of the queue.
     *
     * @param  string  $queue
     * @return integer
     */
    public function size($queue = null)
    {
        // ...
    }

    /**
     * Push a new job onto the queue
     *
     * @param string $job
     * @param mixed  $data
     * @param string $queue
     *
     * @return mixed
     */
    public function push($job, $data = '', $queue = null)
    {
        $payload = $this->createPayload($job, $data);

        return $this->pushRaw($payload, $queue);
    }

    /**
     * Push a raw payload onto the queue
     *
     * @param string $payload
     * @param string $queue
     * @param array  $options
     *
     * @return mixed
     */
    public function pushRaw($payload, $queue = null, array $options = [])
    {
        $message = new SendMessageRequest($payload);
        $response = $this->mns->setQueue($queue)->sendMessage($message);

        return $response->getMessageId();
    }

    /**
     * Push a new job onto the queue after a delay
     *
     * @param \DateTime|int $delay
     * @param string        $job
     * @param mixed         $data
     * @param string        $queue
     *
     * @return mixed
     */
    public function later($delay, $job, $data = '', $queue = null)
    {
        $seconds = $this->getSeconds($delay);
        $payload = $this->createPayload($job, $data);
        $message = new SendMessageRequest($payload, $seconds);
        $response = $this->mns->setQueue($queue)->sendMessage($message);

        return $response->getMessageId();
    }

    /**
     * Pop the next job off of the queue
     *
     * @param string $queue
     *
     * @return \Illuminate\Queue\Jobs\Job|null
     */
    public function pop($queue = null)
    {
        $queue = $this->getDefaultIfNull($queue);

        try {
            $response = $this->mns->setQueue($queue)->receiveMessage();
        } catch (MessageNotExistException $e) {
            $response = null;
        }

        if ($response) {
            if ($this->jobCreator) {
                return call_user_func($this->jobCreator, $this->container, $queue, $response);
            } else {
                return new MnsJob($this->container, $this->mns, $queue, $response);
            }
        }

        return;
    }

    /**
     * 获取默认队列名（如果当前队列名为 null）
     *
     * @param string|null $wanted
     *
     * @return string
     */
    public function getDefaultIfNull($wanted)
    {
        return $wanted ?: $this->default;
    }

    /**
     * 设置使用特定的回调函数处理 job
     *
     * @param callable $callback
     *
     * @return $this
     */
    public function createJobsUsing(callable $callback)
    {
        $this->jobCreator = $callback;

        return $this;
    }
}
