<?php

namespace Core\Components;

use Core\Interfaces\QueueInterface;

class UrlQueue implements QueueInterface
{
    private $queue = array();

    public function inQueue($list = array())
    {
        $this->queue = $this->queue + $list;
    }

    public function outQueue()
    {
        return array_shift($this->queue);
    }

    public function emptyQueue()
    {
        unset($this->queue);
    }

    public function lengthQueue()
    {
        return count($this->queue);
    }
}