<?php

namespace Core;

/**
 * Class Loader
 * 自动加载类
 * @author LL
 */
class Loader
{
    public static function autoload($class)
    {
        require_once BASEDIR."/".str_replace('\\','/',$class).".php";
    }
}