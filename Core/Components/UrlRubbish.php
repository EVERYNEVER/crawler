<?php

namespace Core\Components;

use Core\Interfaces\UrlRubbishInterface;

class UrlRubbish implements UrlRubbishInterface
{
    private $list = array();

    public function __construct()
    {
        $list = @file_get_contents(BASEDIR."/log/urlrubbish.php");
        if(unserialize($list)){
            //$this->list = unserialize($list);
        }
    }

    public function isInRubbish($value)
    {
        if(empty($this->list)){
            return false;
        }

        return in_array($value,$this->list);
    }

    public function joinRubbish($value)
    {
        $this->list[] = $value;
        $this->saveRubbish();
    }

    private function saveRubbish()
    {
        file_put_contents(BASEDIR."/log/urlrubbish.php",serialize($this->list));
    }
}