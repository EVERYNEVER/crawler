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
        //如果这个url已经存在于垃圾堆中，则不插入队列
        array_walk($urlArray,function($value,$key){
            if($this->UrlRubbish->inRubbish(md5($value))){
                unset($value);
            }
        });

        $this->UrlQueue->inQueue($urlArray);
    }

    public function getUrl()
    {
        $url = $this->UrlQueue->outQueue();

        //将返回的url加入垃圾堆中
        $this->UrlRubbish->joinRubbish(md5($url));

        return $url;
    }
}