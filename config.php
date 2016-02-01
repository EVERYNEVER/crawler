<?php
	/**
	 * crawler配置文件
	 */

	/**
	 * 应用文件名称
	 */
	$config['app_name'] = '';

	/**
	 * 钩子前缀
	 */
	$config['hook_prefix'] = 'hook_';

	/**
	 * 入口url
	 * 如果为域名，需要在最后加上斜杠，如http://www.baidu.com/
	 */
	$config['start_url'] = 'http://www.jingbo.net/';
	
	/**
	 * 目标网站的域名
	 * 如http://www.baidu.com
	 */
	$config['domain_name'] = 'http://www.jingbo.net';

	/**
	 * 最大层级
	 * 0为无限制
	 */
	$config['level'] = 0;

	/**
	 * 每次执行完的休眠时间
	 */
	$config['sleep_time'] = 0.5;

	/**
	 * 是否开启模拟登录，TRUE开启，FALSE不开启
	 */
	$config['simulation'] = FALSE;

	/**
	 * 模拟登录的帐号
	 */
	$config['account'] = '';

	/**
	 * 模拟登录的密码
	 */
	$config['password'] = '';
?>