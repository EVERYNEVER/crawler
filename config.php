<?php
	/**
	 * crawler配置文件
	 */

	/**
	 * 应用文件名称
	 */
    $config['appName'] = 'App\Test';

	/**
	 * 钩子前缀
	 */
	$config['hookPrefix'] = 'hook';

	/**
	 * 入口url
	 * 如果为域名，需要在最后加上斜杠，如http://www.baidu.com/
	 */
	$config['presentUrl'] = 'http://yinxiaofeng.artron.net/about';
	
	/**
	 * 目标网站的域名
	 * 如baidu.com
	 */
	$config['domain'] = 'artron.net';

    /**
     * 是否只获取域名内的链接，true是，false获取域名外的内容
     */
	$config["isdomain"] = true;

	/**
	 * 最大层级
	 * 0为无限制
	 */
	$config['maxLevel'] = 0;

	/**
	 * 每次执行完的休眠时间
	 */
	$config['sleepTime'] = 1;

    /**
     * 数据库配置
     */
	$config['db'] = [
		"host" => "127.0.0.1",
		"user" => "root",
        "password" => "abc12345",
        "dbname" => "testdb",
	];