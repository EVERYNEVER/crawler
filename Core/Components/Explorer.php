<?php

namespace Core\Components;

use Core\Interfaces\ExplorerInterface;

class Explorer implements ExplorerInterface
{
    private $curl;

    public function __construct(\Core\Curl $curl)
    {
        $this->curl = $curl;
    }

    public function openLink($url)
    {
        return $this->curl->openLink($url);
    }
}