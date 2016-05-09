<?php
	/**
	 * 应用类
	 */
	class Capp {

		/**
		 * Crawler对象
		 */
		protected $crawler;

		/**
		 * 构造函数
		 * 引用Crawler对象
		 */
		public function __construct() {
			$this->crawler =& Crawler::get_instance();
		}

		/**
		 * 加载方法
		 * @param string $classname 类名，首字母需小写
		 */
		public function load($classname) {
			$this->$classname =& Common::load_class($classname);
		}
	}