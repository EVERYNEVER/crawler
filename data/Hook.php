<?php

	/**
	 * 钩子类
	 * 用于设置钩子和调用钩子
	 */

	class Hook {

		/**
		 * 钩子函数的前缀
		 */
		private $hook_prefix;

		/**
		 * 应用对象
		 */
		private $app;

		/**
		 * hook的构造函数
		 * 检出应用是否存在，并实例化应用类
		 * 检出钩子前缀是否正确设置
		 */
		public function __construct() {
			$CRAWLER =& Crawler::get_instance();

			/**
			 * 从配置文件中获取钩子函数的前缀，如果没有设置，默认为hook_，并验证是否合法
			 */
			$this->hook_prefix = $CRAWLER->config->get('hook_prefix') ? $CRAWLER->config->get('hook_prefix') : 'hook_';
			if(!preg_match('/^.+_$/',$this->hook_prefix)){
				die("钩子函数前缀错误！");
			}

			/**
			 * 从配置文件中获取应用文件名称，如果没有设置，默认为index
			 * 引用应用文件
			 */
			$class_name = $CRAWLER->config->get('app_name') ? $CRAWLER->config->get('app_name') : 'index';
			$class_name = ucfirst($class_name);
			if(file_exists(APP_PATH.$class_name.'.php')){
				require_once APP_PATH.$class_name.'.php';
				$this->app = new $class_name();
			}
			else{
				die("应用文件不存在！");
			}
		}

		/**
		 * 执行钩子函数
		 * @param string $hook_name 钩子的名称
		 * @param array $params 向钩子函数中传入的参数
		 */
		public function exe_hook($hook_name,$params=array()) {
			if(method_exists($this->app,$this->hook_prefix.$hook_name)){
				call_user_func_array(array($this->app,$this->hook_prefix.$hook_name),$params);
			}
		}
	}
?>