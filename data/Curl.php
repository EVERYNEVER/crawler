<?php
	/**
	 * curl操作类
	 */	

	class Curl {

		/**
		 * 通过curl获取网页内容
		 * @param $url string 目标url地址
		 * @return 成功时返回网页内容，失败时返回false
		 */
		public function get_contents($url) {
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_HEADER,false);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			$info = curl_exec($ch);
			curl_close($ch);
			return $info;
		}
	}
?>