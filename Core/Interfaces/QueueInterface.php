<?php

namespace Core\Interfaces;

/**
 * Interface QueueInterface
 * 队列接口
 * @author LL
 */
interface QueueInterface
{
    /**
     * 入队
     * @param array $list
     */
    public function inQueue($list = array());

    /**
     * 出队
     */
    public function outQueue();

    /**
     * 清空队列
     */
    public function emptyQueue();

    /**
     * 获取队列长度
     */
    public function lengthQueue();
}