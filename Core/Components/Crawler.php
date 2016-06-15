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
        $this->maxLevel = $maxLevel;
        $this->sleepTime = $sleepTime;
        $this->presentLevel = 1;

        Component::GuideUrl()->setUrl([$presentUrl]);
    }

    public function run()
    {
        //init钩子，当爬虫初始化后运行的钩子，没有任何参数
        Component::Hook()->exeHook("init");

        while($this->stop()){
            $this->nextUrl();
            $this->getContent();
            $this->increaceLevel();
            $this->sleep();
        }
    }

    public function nextUrl()
    {
        $this->presentUrl = Component::GuideUrl()->getUrl();
    }

    public function increaceLevel()
    {
        $this->presentLevel++;
    }

    public function getContent()
    {
        $data = Component::Explorer()->openLink($this->presentUrl);

        //如果这个链接没有获取到内容，则递归这个方法，直到获取到内容
        if(!$data){
            //voidurl钩子，在获取无效链接后执行的钩子，会传递一个参数$url
            Component::Hook()->exeHook("voidurl",["url" => $this->presentUrl]);

            //这里主要是为了检查是否还存在链接
            $this->stop();
            $this->nextUrl();
            $this->sleep();
            $this->getContent();
        } else {
            //opened钩子，在获取有效数据后执行的钩子，会传递两个参数$data，$url
            //$data是数据内容
            //$url是当前的url
            Component::Hook()->exeHook("opened",["data" => $data,"url" => $this->presentUrl]);

            Component::Filter()->setRuleIndex($this->presentUrl);

            //beforefilter钩子，在设置完规则索引后执行，会传递一个参数$url
            //$url是当前访问的url
            Component::Hook()->exeHook("beforefilter",["url" => $this->presentUrl]);

            $filteredData = Component::Filter()->exeRule($data);

            //filtered钩子，当爬虫过滤完数据后执行的钩子，会传递一个数组$filteredData
            //filteredHref，过滤出来的链接数组
            //filteredHtml，过滤出来的页面内容数组
            Component::Hook()->exeHook("filtered",["filteredData" => $filteredData]);

            Component::GuideUrl()->setUrl($filteredData["filteredHref"]);
        }
    }

    public function stop()
    {
        $stop = false;

        if(Component::UrlQueue()->lengthQueue() == 0){
            $stop = true;
        }
        if($this->presentLevel == $this->maxLevel && $this->maxLevel != 0){
            $stop = true;
        }

        if($stop){
            //stop钩子，当爬虫停止前执行的钩子，没有任何参数
            Component::Hook()->exeHook("stop");

            exit;
        } else {
            return true;
        }
    }

    private function sleep()
    {
        sleep($this->sleepTime);
    }

    public function getPresentUrl()
    {
        return $this->presentUrl;
    }
}