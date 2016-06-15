<?php

namespace Core\Components;

use Core\Interfaces\QueueInterface;

class UrlQueue implements QueueInterface
{
    private $queue = array();

    public function __construct()
    {
        $queue = @file_get_contents(BASEDIR."/log/urlqueue.php");
        if(unserialize($queue)){
            //$this->queue = unserialize($queue);
        }
    }

    public function inQueue($list = array())
    {
        if(!empty($list)) {
            $this->queue = array_merge($this->queue, $list);
            $this->saveQueue();
        }
    }

    public function outQueue()
    {
        $list = array_shift($this->queue);
        $this->saveQueue();
        return $list;
    }

    public function emptyQueue()
    {
        unset($this->queue);
        $this->saveQueue();
    }

    public function lengthQueue()
    {
        return count($this->queue);
    }

    public function isInQueue($value)
    {
        return in_array($value,$this->queue);
    }

    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * 保存队列
     */
    private function saveQueue()
    {
        file_put_contents(BASEDIR."/log/urlqueue.php",serialize($this->queue));
    }
}