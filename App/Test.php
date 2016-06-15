<?php

namespace App;

use Core\Component;

class Test
{
    //init钩子，当爬虫初始化后运行的钩子
    public function hookInit()
    {
    }

    //opened钩子，在获取有效数据后执行的钩子
    public function hookOpened($data,$url)
    {
    }

    //voidurl钩子，在获取无效链接后执行的钩子
    public function hookVoidurl($url)
    {
    }

    //beforefilter钩子，在确定完规则索引后执行
    public function hookBeforefilter($url)
    {
    }

    //filtered钩子，当爬虫过滤完数据后执行的钩子
    //filteredHref，过滤出来的链接数组
    //filteredHtml，过滤出来的页面内容数组
    public function hookFiltered($filteredData)
    {
    }

    //stop钩子，当爬虫停止前执行的钩子
    public function hookStop()
    {
    }
}