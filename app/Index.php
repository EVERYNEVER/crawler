<?php
	
	class Index extends Capp{

		public function hook_start() {

		}

		public function hook_filter_start($level,$url,$content) {

		}

		public function hook_filter_end($level,$data,$url_array) {
			static $num = 0;
			
			var_dump($this->crawler->url_array);
			$num++;

			if($num == 5){
				die();
			}
		}

		public function hook_invalid_url($url) {

		}

		public function hook_end() {

		}
	}
?>