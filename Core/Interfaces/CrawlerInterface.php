<?php

namespace Core\Interfaces;

interface CrawlerInterface
{
    public function __construct($maxLevel,$presentUrl,$sleepTime);

    public function run();
}