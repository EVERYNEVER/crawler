<?php

namespace Core\Interfaces;

interface ExplorerInterface
{
    /**
     * 打开一个链接并获取内容
     * @param string $url 链接地址
     */
    public function openLink($url);
}