<?php

/**
 * 组件配置
 */

$components = [
    "Crawler" => function($maxLevel,$presentUrl,$sleepTime){
        return new Core\Components\Crawler($maxLevel,$presentUrl,$sleepTime);
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
    "Hook" => function(){
        return new Core\Hook();
    },
    "Filter" => function(){
        return new Core\Components\HtmlDomFilter();
    },
    "Curl" => function(){
        return new Core\Curl();
    },
    "Explorer" =>function(){
        return new Core\Components\Explorer(Core\Component::Curl());
    },
    "CorrectHref" => function(){
        return new Core\CorrectHref();
    },
    "DB" => function(){
        return new Extra\DB();
    },
    "HtmlDom" => function(){
        return new Extra\HtmlDom();
    }
];