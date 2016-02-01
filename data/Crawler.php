<?php
	/**
	 * crawler核心程序
	 */

	class Crawler {

		/**
		 * url数组指针
		 */
		public $url_pointer;

		/**
		 * 当前访问页面的url
		 */
		public $url;

		/**
		 * 存储url的数组
		 */
		public $url_array = array();

		/**
		 * crawler对象
		 */
		private static $instance;

		/**
 		 * 当前访问的层级
		 */
		private $level;

		/**
		 * 最大访问层级
		 */
		private $max_level;

		/**
		 * 构造方法
		 * 载入需要的类库
		 * 运行run方法
		 */
		public function __construct() {
			
			/**
			 * 引用crawler对象
			 */
			self::$instance =& $this;

			/**
			 * 加载默认的类库
			 */
			$this->default_load();

			/**
			 * 获取配置文件中的最大访问层级
			 */
			$this->max_level = $this->config->get('level') ? $this->config->get('level') : 0;

			/**
			 * 开始执行crawler
			 */
			$this->run();
		}

		/**
		 * 返回crawler对象
		 */
		public static function &get_instance() {
			return self::$instance;
		}

		/**
		 * 加载默认的类库
		 */
		private function default_load() {
			$load_array = array('config','filterhtml','hook','processurl');

			foreach($load_array as $val) {
				$this->load_class($val);
			}
		}

		/**
		 * 加载指定的类
		 * @param string $classname 类名，首字母需小写
		 */
		private function load_class($classname) {
			$this->$classname =& Common::load_class($classname);
		}

		/**
		 * crawler运行方法
		 */
		private function run() {

			/**
			 * 判断入口url是否符合规则
			 */
			if(!preg_match('/(http|https):\/\/[^\/]*\/.*/',$this->config->get('start_url'))) {
				die("\n入口url不符合规则！");
			}

			/**
			 * start钩子执行
			 * 这个钩子是crawler开始运行前执行的
			 */
			$this->hook->exe_hook('start');

			/**
			 * 设置当前层级为0
			 */
			$this->level = 0;

			/**
			 * 获取休眠时间
			 */
			$sleep_time = $this->config->get('sleep_time') ? $this->config->get('sleep_time') : 0.5;

			/**
			 * 将入口地址加入到url数组中
			 */
			$this->processurl->update_url_array(array(0 => $this->config->get('start_url')));
			
			/**
			 * 调整指针指向第一个也是目前唯一一个url地址
			 */
			$this->set_url_pointer();

			while(!empty($this->url_pointer)){

				/**
				 * 获取url地址
				 */
				$this->url = $this->url_array[$this->url_pointer]['url'];
				
				/**
				 * 过滤页面内容
				 */
				$filter_url = $this->enter_page();

				/**
				 * 更新url数组中url的状态为2
				 * 即被访问过的url
				 */
				$this->url_array[$this->url_pointer]['status'] = 2;

				/**
				 * 更新url数组
				 */
				$this->sorting_url($filter_url);

				/**
				 * 调整url指针指向下一个url
				 */
				$this->set_url_pointer();

				sleep($sleep_time);
			}

			/**
			 * end钩子执行
			 * 这个钩子是在crawler运行结束时执行的
			 */
			$this->hook->exe_hook('end');

			die("\n程序结束");
		}

		/**
		 * crawler获取链接并打开这个链接获得内容
		 * 如果是入口域名，则会停止程序运行
		 */
		private function enter_page() {
			$content = @file_get_contents($this->url);
			if($content){

				/**
				 * filter_start钩子执行
				 * 这个钩子是当前页面内容过滤前执行的
				 * @param int $this->level 当前的层级
				 * @param string $this->url 进入这个页面的url
				 * @param string $content 页面的内容 
				 */
				$this->hook->exe_hook('filter_start',array($this->level,$this->url,$content));

				$filter_data = $this->filterhtml->fileter($content,$this->url);

				/**
				 * filter_end钩子执行
				 * 这个钩子是当页面内容过滤完后执行的
				 * @param int $this->level 当前的层级
				 * @param array $filter_data['data'] 根据其它过滤规则过滤出来的数据
				 * @param array $filter_data['url'] 根据url过滤规则过滤出来的数据
				 */
				$this->hook->exe_hook('filter_end',array($this->level,$filter_data['data'],$filter_data['url']));

				return $filter_data['url'];
			}
			else{
				if($this->level == 1){
					die("\n这是一个无效的域名！");
				}
				else{

					/**
					 * invalid_url钩子执行
					 * 这个钩子是crawler打开了一个无效链接时执行的
					 * @param string $this->url 无效链接的地址
					 */
					$this->hook->exe_hook('invalid_url',array($this->url));

					return FALSE;
				}
			}
		}

		/**
		 * 对所有链接进行整理
		 * @param $url_array array 要新增加的url数组
		 */
		private function sorting_url($url_array) {

			if($url_array && ($this->level < $this->max_level || $this->max_level == 0)){

				/**
				 * 更新url数组
				 */
				$this->processurl->update_url_array($url_array);
			}
		}

		/**
		 * 调整url_pointer指向下一个链接
		 */
		private function set_url_pointer() {
			
			/**
			 * 记录url指针需要指向数组中第几个元素
			 */
			static $max_pointer = 1;

			/**
			 * 强制调整数组指针指向第一个元素
			 */
			reset($this->url_array);

			/**
			 * 根据$max_pointer循环url数组，获取到相应位置的元素
			 */
			$current_pointer = 1;
			while($current_pointer<=$max_pointer){
				$url_info = each($this->url_array);
				$current_pointer++;
			}

			if($url_info) {

				/**
				 * 设置url指针
				 */
				$this->url_pointer = $url_info['key'];

				/**
				 * 设置下一次的访问层级
				 */
				$this->level = $url_info['value']['level'];
			}
			else{
				$this->url_pointer = '';
			}

			$max_pointer++;
		}

		/**
		 * 获取当前层数
		 */
		public function get_level() {
			return $this->level;
		}
	}
?>