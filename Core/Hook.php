<?php

namespace Core;

use Core\Component;

class Hook
{
	/**
	 * 应用对象
	 */
	private $app;

    /**
     * 钩子前缀
     */
    private $hookPrefix;

	/**
	 * 构造函数
     * 实例化应用类
	 */
	public function __construct()
    {
        $this->hookPrefix = Component::Config()->get("hookPrefix");
        $app = Component::Config()->get("appName");

        $this->app = new $app();
	}

	/**
	 * 执行钩子函数
	 * @param string $hookName 钩子的名称
	 * @param array $params 向钩子函数中传入的参数
	 */
	public function exeHook($hookName,$params = array())
    {
        $hookName = ucfirst($hookName);

		if(method_exists($this->app,$this->hookPrefix.$hookName)){
			call_user_func_array(array($this->app,$this->hookPrefix.$hookName),$params);
		}
	}
}