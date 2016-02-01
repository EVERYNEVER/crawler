<?php
	/**
	 * 配置文件类
	 * 用于获得配置信息和设置配置信息
	 */

	class Config {

		/**
		 * 配置文件数组
		 */
		private $config = array();

		/**
		 * 构造函数，引入config.php配置文件
		 */
		public function __construct() {
			require_once CRAWLER.'config.php';
			$this->config = $config;
		}

		/**
		 * 获取配置
		 * @param string $config_name 配置信息名称
		 */
		public function get($config_name) {
			return $this->config[$config_name];
		}


		/**
		 * 设置配置
		 * @param string $config_name 配置信息名称
		 * @param string/int/array $value 要设置配置信息的值
		 */
		public function set($config_name,$value) {
			$this->config[$config_name] = $value;
		}
	}
?>