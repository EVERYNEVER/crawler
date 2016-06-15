<?php

namespace Core\Interfaces;

interface CrawlerInterface
{
    /**
     * 爬虫的构造方法，用于初始化爬虫，这里需要三个参数来设置爬虫
     * @param int $maxLevel 爬取的最大层级数
     * @param string $presentUrl 入口url
     * @param int $sleepTime 爬取完一个页面后的休眠时间，单位为秒
     */
    public function __construct($maxLevel,$presentUrl,$sleepTime);

    /**
     * 爬虫的运行方法，在这个方法里爬虫会真正启动，从入口url进入，开始爬取内容，
     * 调用组件来完成逻辑
     */
    public function run();

    /**
     * 通过这个方法，爬虫来获取下一个进入的url地址
     */
    public function nextUrl();

    /**
     * 通过这个方法，爬虫来增加层级数，并在达到最大层级数时停止爬取
     */
    public function increaceLevel();

    /**
     * 爬虫通过这个方法来真正的获取数据
     */
    public function getContent();

    /**
     * 爬虫的停止方法，这个方法会在爬取完一个页面后执行，来判断是否达到停止条件
     */
    public function stop();
}