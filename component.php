<?php

/**
 * 组件配置
 */

$components = [
    "Crawler" => function($maxLevel,$presentUrl,$sleepTime){
        return new Core\Components\Crawler($maxLevel,$presentUrl,$sleepTime);
    },
    "ProcessUrl" => function(){
        return new Core\Components\ProcessUrl();
    },
    "Config" => function(){
        return new Core\Components\Config();
    },
    "GuideUrl" => function(){
        return new Core\Components\GuideUrl(Core\Component::UrlQueue(),Core\Component::UrlRubbish());
    },
    "UrlQueue" => function(){
        return new Core\Components\UrlQueue();
    },
    "UrlRubbish" => function(){
        return new Core\Components\UrlRubbish();
    },
];