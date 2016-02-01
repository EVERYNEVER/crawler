<?php
	/**
	 * 公共类
	 */
	class Common {

		/**
		 * 加载文件
		 * @param string $classname 需要加载文件的名称
		 */
		public static function &load_class($classname) {
			static $class_array = array();

			if(isset($class_array[$classname])){
				return $class_array[$classname];
			}

			$class_file = ucfirst($classname);

			if(file_exists(CRAWLER_DATA.$class_file.'.php')){
				require_once(CRAWLER_DATA.$class_file.'.php');
				$class_array[$classname] = new $class_file();
				return $class_array[$classname];
			}
			else{
				if(file_exists(APP_EXTENDS_PATH.$class_file.'.php')){
					require_once(APP_EXTENDS_PATH.$class_file.'.php');
					$class_array[$classname] = new $class_file();
					return $class_array[$classname];
				}
				else{
					die($classname."不存在！");
				}
			}
		}
	}
?>