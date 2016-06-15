<?php

namespace Core;

use Core\ClassFactory;

/**
 * Class Component
 * 组件类，所有组件通过这个类实现调用
 * @author LL
 */
class Component
{
    /**
     * 存储组件的数组
     */
    private static $components = array();

    /**
     * 禁止该对象被实例化
     */
    private function __construct()
    {
    }

    /**
     * 通过获取属性的方式，可以直接获得已被生产的组件
     */
    public function __get($name)
    {
        return isset(self::$components[$name]) ? self::$components[$name] : "";
    }

    /**
     * 通过调用方法的方式，生产一个组件
     */
    public function __call($name, $arguments = array())
    {
        if(isset(self::$components[$name])) {
            return self::$components[$name];
        } else {
            self::$components[$name] = ClassFactory::make($name,$arguments);
            return self::$components[$name];
        }
    }

    /**
     * 通过调用静态方法的方式，生产一个组件
     */
    public static function __callStatic($name, $arguments = array())
    {
        if(isset(self::$components[$name])) {
            return self::$components[$name];
        } else {
            self::$components[$name] = ClassFactory::make($name,$arguments);
            return self::$components[$name];
        }
    }

    /**
     * 通过静态方法获取这个类
     */
    public static function getComponent()
    {
        return new static();
    }
}