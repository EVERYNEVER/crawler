<?php

namespace Core\Components;

use Core\Interfaces\CrawlerInterface;
use Core\Component;

/**
 * Class Crawler
 * crawler核心程序
 * @author LL
 */
class Crawler implements CrawlerInterface
{

    /**
     * 当前访问的url
     */
    private $presentUrl;

    /**
     * 当前访问层级
     */
    private $presentLevel;

    /**
     * 最大访问层级
     */
    private $maxLevel;

    /**
     * 爬取完一个页面后的休眠时间
     */
    private $sleepTime;

    /**
     * 构造方法
     * @parem int $maxLevel 最大访问层数
     * @param string $presentUrl 当前访问url
     * @param int $sleepTime 爬取完一个页面的休眠时间
     */
    public function __construct($maxLevel,$presentUrl,$sleepTime)
    {
        $this->presentUrl = $presentUrl;
        $this->maxLevel = $maxLevel;
        $this->sleepTime = $sleepTime;
        $this->presentLevel = 1;
    }

    public function run()
    {

    }
}