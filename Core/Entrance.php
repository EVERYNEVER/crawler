<?php

namespace Core;

use Core\ClassFactory;
use Core\Component;

/**
 * Class Entrance
 * 框架启动类
 * 需要使用一个ConfigInterface的实现类来调用配置文件
 * 使用一个CrawlerInterface的实现类来启动爬虫
 */
class Entrance
{
    private $config;

    private $components;

    /**
     * 启动器的构造函数，注册component文件中的组件，并运行crawler
     * @param Interfaces\ConfigInterface $Config 配置文件类
     * @param array $components 需要注册的所有组件
     */
    public function __construct(Interfaces\ConfigInterface $Config, $components)
    {
        $this->config = $Config;
        $this->components = $components;

        $this->bind();

        $presentUrl = $this->config->get("presentUrl");
        $maxLevel = $this->config->get("maxLevel");
        $sleepTime = $this->config->get("sleepTime");

        //调用crawler组件
        $this->begin(Component::Crawler($maxLevel,$presentUrl,$sleepTime));
    }

    /**
     * 将所有组件注册
     */
    private function bind()
    {
        foreach($this->components as $k=>$v){
            ClassFactory::bind($k,$v);
        }
    }

    /**
     * 使用crawler的run方法，启动爬虫
     * @param Interfaces\CrawlerInterface $Crawler 一个实现CrawlerInterface的类，这个类是爬虫的实体
     */
    private function begin(Interfaces\CrawlerInterface $Crawler)
    {
        try {
            $Crawler->run();
        } catch(\Exception $e){
            echo $e->getMessage();
        }
    }
}