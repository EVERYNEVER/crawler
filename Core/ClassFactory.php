<?php

namespace Core;

/**
 * Class ClassFactory
 * 组件工厂类
 * @author LL
 */
class ClassFactory
{
    /**
     * 存储所有组件的数组
     */
	private static $instances = array();

    /**
     * 组件注册
     * @param string $className 这个组件的注册名称
     * @param \Closure $concrete 闭包函数，这个组件的实现方法
     */
    public static function bind($className,\Closure $concrete)
    {
        if(!($concrete instanceof \Closure)){
            throw new \Exception('$concrete must Closure');
        }

        if(!isset($instances[$className])) {
            self::$instances[$className] = $concrete;
        }
    }

    /**
     * 生产组件
     * @param string $className 组件的注册名称
     * @param array $parames 生产这个组件需要的参数
     * @return mixed 组件对象
     */
	public static function make($className,$parames = array())
    {
        if(self::$instances[$className] instanceof \Closure){
            self::$instances[$className] = call_user_func_array(self::$instances[$className],$parames);
            return self::$instances[$className];
        } else {
            return self::$instances[$className];
        }
    }
}
?>