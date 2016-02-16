<?php

	/**
 	* crawler入口文件
 	*/

	/**
	 * 设置程序运行时间为永久
	 */
	set_time_limit(0);

	header("Content-type: text/html; charset=utf-8");

	/**
	 * crawler路径
	 */
    define('CRAWLER',__DIR__.'/');

	/**
	 * crawler文件路径
	 */
	define('CRAWLER_DATA',CRAWLER.'data/');

	/**
	 * 应用程序路径
	 */
	define('APP_PATH',CRAWLER.'app/');

	/**
	 * 应用扩展路径
	 */
	define('APP_EXTENDS_PATH',APP_PATH.'extends/');

	/**
	 * 引入公共类
	 */
	require_once CRAWLER_DATA.'Common.php';

	/**
	 * 引入应用类
	 */
	require_once CRAWLER_DATA.'Capp.php';

	/**
	 * 引入crawler文件
	 */
	require_once CRAWLER_DATA.'Crawler.php';
	
	$CRAWLER = new Crawler();
	$CRAWLER->run();
?>