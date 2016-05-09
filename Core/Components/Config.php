<?php

namespace Core\Components;

use Core\Interfaces\ConfigInterface;

/**
 * Class Config
 * 配置文件类
 * 用于获得配置信息和设置配置信息
 * @authro LL
 */
class Config implements ConfigInterface
{

	/**
	 * 配置文件数组
	 */
	private $config = array();

	/**
	 * 构造函数，引入config.php配置文件
	 */
	public function __construct()
    {
		require_once BASEDIR.'/config.php';
		$this->config = $config;
	}

	/**
	 * 获取配置
	 * @param string $config_name 配置信息名称
	 */
	public function get($configName)
    {
		return $this->config[$configName];
	}


	/**
	 * 设置配置
	 * @param string $config_name 配置信息名称
	 * @param mingle  要设置配置信息的值
	 */
	public function set($configName,$value)
    {
		$this->config[$configName] = $value;
	}
}