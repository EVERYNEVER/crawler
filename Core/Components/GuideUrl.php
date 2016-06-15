<?php

namespace Core\Components;

use Core\Component;
use Core\Interfaces\UrlInterface;

class GuideUrl implements UrlInterface
{
    private $UrlQueue;

    private $UrlRubbish;

    public function __construct(\Core\Interfaces\QueueInterface $urlQueue, \Core\Interfaces\UrlRubbishInterface $urlRubbish)
    {
        $this->UrlQueue = $urlQueue;
        $this->UrlRubbish = $urlRubbish;
    }

    public function setUrl($urlArray = array())
    {
        $newUrlArray = array();

        if($urlArray) {
            //如果这个url已经存在于垃圾堆中或urlQueue中，则不插入队列
            array_walk($urlArray, function ($value, $key) use (&$newUrlArray) {
                //去除掉链接最后的/
                $value = trim($value, "/ ");

                if (!$this->UrlRubbish->isInRubbish(md5($value)) && !$this->UrlQueue->isInQueue($value) && !in_array($value, $newUrlArray)) {
                    $newUrlArray[] = $value;
                }
            });
        }

        $this->UrlQueue->inQueue($newUrlArray);
    }

    public function getUrl()
    {
        $url = $this->UrlQueue->outQueue();

        //将返回的url加入垃圾堆中
        $this->UrlRubbish->joinRubbish(md5($url));

        return $url;
    }
}