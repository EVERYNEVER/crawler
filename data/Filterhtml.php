<?php
	/**
	 * 过滤器类
	 * 根据自定义规则过滤网页中的数据，获得需要的链接
	 */

	class Filterhtml {

		/**
		 * 网站域名
		 */
		private $domain_name;

		/**
		 * url_rule是否为用户自定义
		 * 如果用户使用set_url_rule函数设置url_rule
		 * 则该属性的值为TRUE
		 * 程序不会自动调用默认的url处理方法
		 */
		private $is_custom = FALSE;

		/**
		 * 页面内容
		 */
		private $html;

		/**
		 * 规则索引
		 * 判断当前页面需要使用哪个规则进行过滤
		 */
		private $index_rule = array(1 => 'empty');

		/**
		 * 过滤出url的规则
		 */
		private $url_rule = array(0 => '/<[aA].*[hH][rR][eE][fF]=["\']([^"\']*)["\'].*<\/[aA]>/');

		/**
		 * 其它标签或内容过滤规则
		 */
		private $other_rule = array(0 => array('img'=>'/<[iI][mM][gG].*[sS][rR][cC]=["\']([^"\']*)["\'][^<]*/'));

		/**
		 * 经过正则过滤出来的url
		 */
		private $worked_url = array();

		/**
		 * Filterhtml构造方法
		 * 获取配置文件中的站点域名
		 */
		public function __construct(){
			$CRAWLER =& CRAWLER::get_instance();
			$this->domain_name = $CRAWLER->config->get('domain_name');
		}

		/**
		 * 设置url的过滤规则
		 * @param string $rule 过滤规则
		 * @param int $index 规则索引，默认为0
		 */
		public function set_url_rule($rule,$index = 0) {
			$this->url_rule[$index] = $rule;
			$this->is_custom = TRUE;
		}

		/**
		 * 设置其它过滤规则
		 * @param array $rule 过滤规则数组，key为规则的名称，value为匹配规则
		 * @param int $index 规则索引，默认为0
		 */
		public function set_other_rule($rule = array(),$index = 0) {
			$this->other_rule[$index] = $rule;
		}

		/**
		 * 设置规则索引
		 * @param string $rule 匹配规则
		 * @param int $index 这条规则的索引值，默认为1
		 */
		public function set_index_rule($rule,$index = 1) {

			if($index == 0) {
				die("\n规则索引不能为0！");
			}
			$this->index_rule[$index] = $rule;
		}

		/**
  		 * 处理html内容
  		 * @param string $html 需要处理的html内容
  		 * @param string $current_url 当前的url地址
		 */
		public function fileter($html,$current_url) {
			$fileter_data = array();
			$this->html = $html;

			/**
			 * 判断rule的索引
			 */
			$_rule_index = $this->choice_index_rule($current_url);
			
			/**
			 * 获取内容的有效的url链接
			 */
			$data_url = $this->fileter_url($_rule_index);
			
			/**
			 * 如果url_rule为用户自己设置的，则不执行默认的过滤规则
			 */
			if(!$this->is_custom){
				$data_url = $this->splice_url($data_url);
				$data_url = $this->filter_surplus_url($data_url);
			}
			
			$fileter_data['url'] = $data_url;
			
			/**
			 * 根据其它规则过滤数据
			 */
			$fileter_data['data'] = $this->fileter_other($_rule_index);

			return $fileter_data;
		}

		/**
		 * 根据url判断当前url的规则索引
		 * @param string $url url地址
		 * @return int 当前url的规则索引的key，如果没有发现有效的规则索引，则默认为0
		 */
		private function choice_index_rule($url) {
			$rule = '/(http|https):\/\/[^\/]*\//';

			/**
			 * 去掉url中的域名部分
			 */
			if(preg_match($rule,$url)){
				$_url = preg_replace($rule,"",$url);
			}
			else{
				$_url = $url;
			}
			
			$_index = 0;

			if(is_array($this->index_rule) && !empty($this->index_rule)){

				/**
				 * 如果去掉域名的url为空
				 * 则规则索引的判断方式为等于empty
				 * 如果不为空
				 * 则判断url符合哪个规则索引中的匹配规则
				 */
				if(empty($_url)){
					foreach($this->index_rule as $key=>$rule_value) {
						if($key != 0) {
							if($rule_value == 'empty'){
								$_index = $key;
								break;
							}
						}
					}
				}
				else{
					foreach($this->index_rule as $key=>$rule_value) {
						if($key != 0 && $rule_value != 'empty') {
							if(preg_match($rule_value,$_url)) {
								$_index = $key;
								break;
							}
						}
					}
				}
			}

			return $_index;
		}

		/**
		 * 根据url过滤规则，筛选出页面中符合规则的url
		 * @param $key 规则数组中的键
		 * @return array/bool 返回过滤出来的url数组，如果没有符合的，则返回FALSE
		 */
		private function fileter_url($key) {

			/**
			 * 判断当前索引是否存在url规则
			 * 如果不存在则使用索引为0的规则
			 */
			$url_rule = isset($this->url_rule[$key]) ? $this->url_rule[$key] : $this->url_rule[0];

			preg_match_all($url_rule,$this->html,$data_url);

			if(empty($data_url[1])){
				return FALSE;
			}
			else{
				return $data_url[1];
			}
		}

		/**
		 * 根据用户设置的过滤规则，对页面内容进行过滤
		 * @param $key 规则数组中的键
		 */
		private function fileter_other($key) {

			/**
			 * 如果当前索引设置了过滤规则，则使用当前索引的过滤规则
			 * 如果没有设置则使用默认的过滤规则
			 */
			if(isset($this->other_rule[$key])){
				$rule_array = $this->other_rule[$key];
			}
			else{
				$rule_array = $this->other_rule[0];
			}

			foreach($rule_array as $tag=>$rule) {
				preg_match_all($rule,$this->html,$rule_data);
				$data[$tag] = $rule_data[1];
			}

			return $data;
		}

		/**
	 	 * 处理url链接，拼接域名
	 	 * @param array $data 要处理的url数组
		 */
		public function splice_url($data) {
			if(is_array($data)){
				foreach($data as $k=>$v){
					if(!preg_match("/(http|https):\/\/.*/",$v)){
						$data[$k] = $this->domain_name.$v;
					}
				}

				return $data;
			}
			else{
				return FALSE;
			}
		}

		/**
		 * 根据配置文件中的域名过滤掉外链
		 * @param array $data 要过滤的url数组
		 */
		public function filter_surplus_url($data) {
			if(is_array($data)){
				$domain_name = str_replace("/","\/",$this->domain_name);

				foreach($data as $k=>$v){
					if(preg_match("/".$domain_name.".*/",$v)){
						$new_data[] = $v;
					}
				}

				return $new_data;
			}
		}
	}
?>