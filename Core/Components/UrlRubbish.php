<?php

namespace Core\Components;

use Core\Interfaces\UrlRubbishInterface;

class UrlRubbish implements UrlRubbishInterface
{
    private $list = array();

    public function inRubbish($value)
    {
        if(empty($this->list)){
            return false;
        }

        return in_array($value,$this->list);
    }

    public function joinRubbish($value)
    {
        $this->list[] = $value;
    }
}