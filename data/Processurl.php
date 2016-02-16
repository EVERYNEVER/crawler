<?php

	class Processurl {

		/**
		 * 更新crawler的url数组
		 * @param array $url_array 要处理的url数组
		 */
		public function update_url_array($url_array) {
			$CRAWLER =& Crawler::get_instance();
			$new_url_array = $this->machining_url($url_array);

			$level = $CRAWLER->get_level();
			foreach($new_url_array as &$v) {
				$v['level'] = $level+1;
				$v['status'] = 1;
			}

			$CRAWLER->url_array = $CRAWLER->url_array + $new_url_array;
		}

		/**
		 * 对url数组建立索引
		 * @param array $url_array 要建立索引的数组
		 */
		public function machining_url($url_array) {
			$data = array();
			foreach($url_array as $v) {
				$data[md5($v)]['url'] = $v;
			}

			return $data;
		}
	}
?>